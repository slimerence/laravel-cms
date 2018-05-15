<?php
/**
 * 购物车控制器类
 */
namespace App\Http\Controllers\Frontend;

use App\Models\Catalog\Product;
use App\Models\Catalog\Product\ProductOption;
use App\Models\Catalog\Product\OptionItem;
use App\Models\Utils\JsonBuilder;
use App\Models\Utils\OptionTool;
use App\Models\Utils\ProductType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Exceptions\InvalidRowIDException;
use Gloudemans\Shoppingcart\Exceptions\CartAlreadyStoredException;

class ShoppingCartController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_cart(Request $request){
        $this->dataForView['cart'] = $this->getCart();
        $cartData = [];
        foreach ($this->getCart()->content() as $item) {
            $cartData[] = $item;
        }
        $this->dataForView['cartData'] = $cartData;
        $this->dataForView['vuejs_libs_required'] = ['cart_view'];
        return view(
            'frontend.default.catalog.cart.view',
            $this->dataForView
        );
    }

    /**
     * 删除购物车的元素
     * @param Request $request
     * @return string
     */
    public function remove_item(Request $request){
        $rowId = $request->get('rowId');
        if($rowId && !empty($rowId)){
            try{
                $this->getCart()->remove($rowId);
                return JsonBuilder::Success();
            }catch (InvalidRowIDException $e){
                // 相当于没有找到该id, 那么也算是删除成功了
                return JsonBuilder::Success();
            }
        }else{
            return JsonBuilder::Error();
        }
    }

    /**
     * 做结账前的最后准备
     * @param Request $request
     * @return string
     */
    public function prepare_checkout(Request $request){
        /**
         * 首先, 将提交来的item进行更新
         */
        $items = $request->get('cart');
        $errMsg = null;
        if($items && count($items)>0){
            $cart = $this->getCart();
            foreach ($items as $item) {
                try{
                    $cart->update($item['rowId'],['qty'=>$item['qty']]);
                }catch (InvalidRowIDException $e){
                    $errMsg = $e->getMessage();
                    break;
                }
            }

            if(is_null($errMsg)){
                // 检查$cart的金额和数量是否正常
                if(floatval($cart->total()) <= 0 || $cart->count() == 0){
                    $errMsg = 'Something wrong in shopping cart!';

                    // 购物车的金额或者产品数量不对, 直接返回错误
                    return JsonBuilder::Error($errMsg);
                }else{
                    // 暂时没有什么特殊的问题, 可以继续
                    if(config('system.ORDER_MUST_BE_PAID_ONLINE')){
                        // 如果必须要在线支付, 那么就进入 one step checkout 流程
                        // Todo: 一步结账功能

                    }else{
                        // 既然不需要在线支付, 那么就检查用户的登录信息, 如果有, 就可以直接产生订单了
                        if(session('user_data.id')){
                            // 那么用户已经登录, 就跳转到 Place Order Number的页面, 这个页面必须是已经登录的用户才能访问
                            return JsonBuilder::Success(url('frontend/place_order_checkout'));
                        }else{
                            // 用户还没有登录或者session过期, 显示登录界面
//                            return JsonBuilder::Success(url('frontend/customers/login'));
                            return JsonBuilder::Success(url('frontend/place_order_checkout'));
                        }
                    }
                }
            }else{
                // 更新购物车的产品数量出错了, 直接返回
                return JsonBuilder::Error($errMsg);
            }
        }
    }

    /**
     * 把产品添加到购物车的方法
     * @param Request $request
     * @return string
     */
    public function add_to_cart(Request $request){
        $items = $request->get('items');
        $data = null;
        $product = null;
        if($items && is_array($items)){
            foreach ($items as $item) {
                if(isset($item['type']) && $item['type'] == 'general' && $item['name'] == 'product_id'){
                    // 获取产品对象
                    $product = Product::GetByUuid($item['value']);
                    break;
                }
            }
        }

        if($product){
            /**
             * 这里有个特殊处理, 如果产品有Option, 那么放入购物车的ID就不能相同,否则会变成产品购物数量的累加
             */
            $idTail = null;
            if(count($product->options())>0){
                $idTail = ProductType::GetProductUuidTail();
            }
            $data = [
                'name'=>null,
                'id'=>$product->uuid.$idTail, // 添加的是否加入Tail
                'qty'=>null,
                'price'=>null,
                'thumbnail'=>null,
                'options'=>[]
            ];
            // 找到了产品
            $data['name'] = $product->name.(!empty($product->unit_text) ? ' ('.$product->unit_text.')' : '');

            $data['options']['thumbnail'] = $product->getProductDefaultImageUrl();
            $data['options']['weight'] = $product->getWeight();
            $data['options']['colour'] = null;

            $data['price'] = $product->getSpecialPriceGST() ? $product->getSpecialPriceGST() : $product->getDefaultPriceGST();
            $data['price'] = floatval(str_replace(',','',$data['price']));

            /**
             * 如果选定了颜色, 那么就处理颜色及其价格增量
             */
            $colour = $request->get('colour');
            if($colour){
                if(isset($colour['type'])){
                    // 先把Color导致的价格变更附加到产品价格上面
                    $data['price'] += $colour['extra_money'];
                    // 对Colour做特殊处理
                    $data['options']['colour'] = $colour;
                }
            }

            /**
             * 同时,还要添加产品option所带来的价格增量
             */
            foreach ($items as $item) {
                if(isset($item['type']) && $item['type'] == 'general' && $item['name'] == 'quantity'){
                    // 获取订购数量
                    $data['qty'] = intval($item['value']);
                }elseif (isset($item['type']) && $item['type'] == 'option' && !empty($item['index'])){
                    // 检查这个提交的产品选项是否存在
                    $po = ProductOption::Exist($item['index'], $product->id);
                    if($po){
                        $option = [];
                        if($po->type == OptionTool::$TYPE_TEXT){
                            // 文本型的option永远不包含价格增量
                            $option['name'] = $po->name;
                            $option['value'] = $item['value'];
                        }elseif ($po->type == OptionTool::$TYPE_DROP_DOWN || $po->type == OptionTool::$TYPE_RADIO_BUTTON){
                            $oi = OptionItem::Exist($item['value'], $po->id);
                            if($oi){
                                $option['name'] = $po->name;
                                $option['value'] = $oi->label;
                                if($oi->extra_value && $oi->extra_value>0){
                                    // 如果该选项需要加钱
                                    $option['value'] .= ' +'.config('system.CURRENCY').number_format($oi->extra_value,2);
                                    $data['price'] += $oi->extra_value;
                                }
                            }
                        }
                        if(!empty($option)){
                            $data['options'][] = $option;
                        }
                    }
                }
            }
        }

        if($data && $cart = $this->getCart()){
            try{
                $cart->add($data);
                return JsonBuilder::Success([
                    'count'=>$cart->content()->count(),
                    'total'=>config('system.CURRENCY').' '.$cart->total()
                ]);
            }catch (CartAlreadyStoredException $e){
                return JsonBuilder::Error($e->getMessage());
            }
        }else{
            return JsonBuilder::Error();
        }
    }
}
