<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Catalog\Brand;
use App\Models\Catalog\Product;
use App\Models\Widget\Block;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Product\Colour;
use App\Models\Catalog\Category;

class Products extends Controller
{
    /**
     * View the single product
     * @param $uri
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($uri, Request $request){
        $product = Product::GetByUri($uri);

        if(!$product){
            return response()->view('frontend.default.pages.404', $this->dataForView, 404);
        }

        $this->dataForView['pageTitle'] = $product->name;
        $this->dataForView['metaKeywords'] = $product->keywords;
        $this->dataForView['metaDescription'] = $product->seo_description;


        $this->dataForView['product'] = $product;
        $this->dataForView['relatedProducts'] = $product->relatedProduct;
        $this->dataForView['product_images'] = $product->get_AllImages();

        /**
         * 产品的属性集的值
         */
        $this->dataForView['product_attributes'] = $product->productAttributes();
        $this->dataForView['product_options'] = $product->options();
        $this->dataForView['product_colours'] = Colour::LoadByProduct($product->id, true)->toArray();
        $this->dataForView['vuejs_libs_required'] = ['product_view'];

        /**
         * 加载通用的产品相关的Blocks
         */
        $this->dataForView['productDescriptionTop'] = Block::where('short_code','like','product_description_block_top%')->get();
        $this->dataForView['productDescriptionBottom'] = Block::where('short_code','like','product_description_block_bottom%')->get();
        $this->dataForView['productShortDescriptionTop'] = Block::where('short_code','like','product_short_description_block_top%')->get();
        $this->dataForView['productShortDescriptionBottom'] = Block::where('short_code','like','product_short_description_block_bottom%')->get();

        return view('frontend.default.catalog.product',$this->dataForView);
    }

    /**
     * 根据品牌加载产品列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_by_brand(Request $request){
        $brand = Brand::where('name',$request->get('name'))->first();

        if(!$brand){
            return response()->view('frontend.default.pages.404', $this->dataForView, 404);
        }

        $this->dataForView['brand'] = $brand;
        $this->dataForView['pageTitle'] = $request->get('name') . ' - ' . str_replace('_',' ',env('APP_NAME'));
        $this->dataForView['metaKeywords'] = $brand->keywords;
        $this->dataForView['metaDescription'] = $brand->seo_description;

        // 加载排序条件
        $orderBy = $request->has('orderBy') ? $request->get('orderBy') : 'position';
        $direction = $request->has('dir') ? $request->get('dir') : 'asc';
        $this->dataForView['orderBy'] = $orderBy;
        $this->dataForView['direction'] = $direction;
        $paginationAppendParams = [
            'orderBy'=>$orderBy,
            'dir'=>$direction
        ];
        $this->dataForView['paginationAppendParams'] = $paginationAppendParams;

        $products = Product::where('brand',$request->get('name'))
            ->orderBy($orderBy, $direction)
            ->paginate(config('system.PAGE_SIZE'));

        // Pagination的对象
        $this->dataForView['products'] = $products;

        // 将价格区间计算出了放到View中
        $this->_calculatePricesRange($products->total(), $request->get('name'));

        $this->dataForView['featureProducts'] = Category::LoadFeatureProducts();

        // 总是加载Features product and promotion
        return view('frontend.default.catalog.brand',$this->dataForView);
    }

    /**
     * 找出给定目录的产品数量的最合适的价格区间数据
     * @param $productsCount
     * @param string $brand
     */
    private function _calculatePricesRange($productsCount, $brand){

        if($productsCount == 0){
            return;
        }

        // 本目录中产品的最低价格
        $lowest_price = intval(Product::where('brand',$brand)->min('default_price'));
        $highest_price = intval(Product::where('brand',$brand)->max('default_price'));
        $ranges = null;
        $count = $productsCount < 5 ? $productsCount : 5;
        $step = intval( ($highest_price - $lowest_price)/$count );
        if($step>1){
            $ranges = range($lowest_price,$highest_price,$step);
        }
        $this->dataForView['price_ranges'] = $ranges;
    }
}
