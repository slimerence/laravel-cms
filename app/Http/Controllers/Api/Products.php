<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Product;
use App\Models\Catalog\Product\ProductOption;
use App\Models\Catalog\Product\OptionItem;
use App\Models\Utils\JsonBuilder;
use App\Models\Catalog\Product\Colour;

class Products extends Controller
{
    /**
     * 删除产品, 通过UUID
     * @param $uuid
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($uuid, Request $request){
        $product = Product::GetByUuid($uuid);

        if($product){
            $productName = $product->name;
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
     * @param Request $request
     * @return stringdelete_colour_ajax
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
        $products = Product::select('name','uri','default_price','special_price','tax')
            ->where('name','like','%'.$queryKeyword.'%')
            ->orderBy('name','asc')
            ->take(10)
            ->get();

        $data = [];
        foreach ($products as $key => $product){
            $data[$key] = [
                'value'=>$product->name.' - '.config('system.CURRENCY').$product->getFinalPriceGst(),
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
}
