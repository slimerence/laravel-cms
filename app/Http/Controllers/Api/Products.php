<?php

namespace App\Http\Controllers\Api;

use App\Models\Catalog\GroupProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Product;
use App\Models\Catalog\Product\ProductOption;
use App\Models\Catalog\Product\OptionItem;
use App\Models\Utils\JsonBuilder;
use App\Models\Catalog\Product\Colour;
use PHPUnit\Util\Json;

class Products extends Controller
{
    /**
     * 删除产品, 通过UUID
     * @param $uuid
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function delete($uuid, Request $request){
        $product = Product::GetByUuid($uuid);

        if($product){
            $productName = $product->getProductName();
            if($product->delete()){
                session()->flash('msg', ['content' => 'Product: "' . $productName . '" has been removed successfully!', 'status' => 'success']);
            }else{
                session()->flash('msg', ['content' => 'Product: "' . $productName . '" cant not be removed!', 'status' => 'danger']);
            }
        }else{
            session()->flash('msg', ['content' => 'Something wrong, please contact Admin!', 'status' => 'danger']);
        }

        return redirect('backend/products');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function clone_product(Request $request){
        $productData = $request->get('product');
        $imagesData = $request->get('images');
        $categoriesData = $request->get('categories');
        $productOptions = $request->get('productOptions');
        $productColours = $request->get('productColours');
        $productAttributeData = $request->get('productAttributeData');

        if(isset($productData['id'])){
//            $originProductId = $productData['id'];
            $productData['id'] = null;
            /**
             * 克隆产品的操作: product 的 ID 必须为空
             */
            $product = Product::DoClone($productData,$imagesData, $categoriesData, $productOptions,$productAttributeData,$productColours);
            if($product){
                return JsonBuilder::Success($product->id);
            }else{
                return JsonBuilder::Error();
            }
        }
    }

    /**
     * 保存产品的信息
     * @param Request $request
     * @return string
     */
    public function save(Request $request){
        $productData = $request->get('product');
        $imagesData = $request->get('images');
        $categoriesData = $request->get('categories');
        $productOptions = $request->get('productOptions');
        $productColours = $request->get('productColours');
        $productAttributeData = $request->get('productAttributeData');

        if(!empty($productData['id'])){
            /**
             * 更新产品的操作
             * 由于产品更新界面前台的处理, 在更新产品的时候,对产品的图片, 产品的Option和 Option的Items
             * 采用的处理方式为: 检查,如果有id就更新,如果没有id就添加。 凡是在前端删除的, 已经在服务器删除了, 并且不会被传到这里
             */
            $product = Product::Persistent($productData,$imagesData, $categoriesData, $productOptions,$productAttributeData,$productColours);
            if($product){
                return JsonBuilder::Success($productData['id']);
            }else{
                return JsonBuilder::Error();
            }
        }else{
            // 添加新产品
            $product = Product::Persistent($productData,$imagesData, $categoriesData, $productOptions,$productAttributeData,$productColours);
            if($product){
                return JsonBuilder::Success($product->id);
            }else{
                return JsonBuilder::Error();
            }
        }
    }

    /**
     * 删除指定的 product option 的 item
     * @param Request $request
     * @return string
     */
    public function delete_option_item_ajax(Request $request){
        $oItem = OptionItem::find($request->get('id'));
        if($oItem){
            if($oItem->delete()){
                return JsonBuilder::Success();
            }
        }
        return JsonBuilder::Error();
    }

    /**
     * 删除产品的指定颜色
     * @param Request $request
     * @return string
     */
    public function delete_colour_ajax(Request $request){
        if(Colour::Terminate($request->get('id'))){
            return JsonBuilder::Success();
        }
        return JsonBuilder::Error();
    }

    /**
     * 删除指定的 product option
     * @param Request $request
     * @return string
     */
    public function delete_options_ajax(Request $request){
        $pOption = ProductOption::find($request->get('id'));
        if($pOption){
            if($pOption->delete()){
                return JsonBuilder::Success();
            }
        }
        return JsonBuilder::Error();
    }

    /**
     * 加载指定的 product options
     * @param Request $request
     * @return string
     */
    public function load_options_ajax(Request $request){
        $data = [
            'options'=>ProductOption::Details($request->get('id')),
            'colours'=>Colour::LoadByProduct($request->get('id'))
        ];

        return JsonBuilder::Success($data);
    }

    /**
     * 搜索产品
     * @param Request $request
     * @return string
     */
    public function ajax_search(Request $request){
        $queryKeyword = strtolower($request->get('key'));
        $products = Product::select('name','name_cn','uri','default_price','special_price','tax')
            ->where('name','like','%'.$queryKeyword.'%')
            ->orWhere('name_cn','like','%'.$queryKeyword.'%')
            ->orderBy('name','asc')
            ->take(10)
            ->get();

        $data = [];
        foreach ($products as $key => $product){
            $data[$key] = [
                'value'=>$product->getProductName().' - '.config('system.CURRENCY').$product->getFinalPriceGst(),
                'id'=>$product->uri,
            ];
        }

        return JsonBuilder::Success($data);
    }

    /**
     * 搜索产品
     * @param Request $request
     * @return string
     */
    public function ajax_search_for_group(Request $request){
        $queryKeyword = strtolower($request->get('key'));
        $excludes = [];
        if($request->has('excludes')){
            // 移除在搜索范围之外的
            $excludes = $request->get('excludes');
        }
        $products = Product::select('name','uri','default_price','special_price','tax','id','is_group_product','is_configurable_product')
            ->where('name','like','%'.$queryKeyword.'%')
            ->whereNotIn('id',$excludes)
            ->where('is_group_product',false)           // 非Group Product
            ->where('is_configurable_product',false)    // 非Configurable Product
            ->orderBy('name','asc')
            ->take(10)
            ->get();

        $data = [];
        foreach ($products as $key => $product){
            $data[$key] = [
                'value'=>$product->name.' - '.config('system.CURRENCY').$product->getFinalPriceGst(),
                'id'=>$product->uri,
                'productId'=>$product->id
            ];
        }
        return JsonBuilder::Success($data);
    }

    /**
     * 保存组合产品的子产品数据
     * @param Request $request
     * @return string
     */
    public function save_group_product(Request $request){
        $gp = $request->get('gp');
        $product = Product::find($gp['product_id']);
        $saved = GroupProduct::create($gp);
        if($saved && $product){
            // 有可能当前产品还不是 Group Product, 因此需要检查, 然后标示一下
            if(!$product->is_group_product){
                $product->is_group_product = true;
                $product->save();
            }
            // 返回所有该产品相关的关联产品列表
            return JsonBuilder::Success($this->_loadGroupedProducts($gp['product_id']));
        }else{
            return JsonBuilder::Error();
        }
    }

    /**
     * 返回给定产品 ID 的 Json 数据
     * @param Request $request
     * @return string
     */
    public function get_group_products(Request $request){
        return JsonBuilder::Success($this->_loadGroupedProducts($request->get('pid')));
    }

    /**
     * 删除组合产品中的某个子产品
     * @param Request $request
     * @return string
     */
    public function delete_group_product(Request $request){
        $deleted = GroupProduct::where('id',$request->get('gpid'))->delete();
        if($deleted){
            return JsonBuilder::Success();
        }else{
            return JsonBuilder::Error();
        }
    }

    /**
     * 获取某个Group 产品中所包含的单个产品的方法
     * @param $productId
     * @return array
     */
    private function _loadGroupedProducts($productId){
        $gps = GroupProduct::where('product_id',$productId)
            ->orderBy('id','desc')
            ->get();
        $result = [];
        foreach ($gps as $gp) {
            $groupProduct = Product::select('name')->where('id',$gp->grouped_product_id)->first();
            $result[] = [
                'id'=>$gp->id,
                'name'=>$groupProduct->name
            ];
        }
        return $result;
    }
}
