<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Catalog\Category;
use App\Models\Catalog\CategoryProduct;
use App\Models\Catalog\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Categories extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 按指定目录加载产品列表
     * @param $uri
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($uri, Request $request){
        $category = Category::where('uri',$uri)->first();

        if(!$category){
            return response()->view('frontend.default.pages.404',$this->dataForView, 404);
        }

        $this->dataForView['pageTitle'] = $category->name . ' - ' . str_replace('_',' ',env('APP_NAME'));
        $this->dataForView['metaKeywords'] = $category->keywords;
        $this->dataForView['metaDescription'] = $category->seo_description;

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

        $cps = CategoryProduct::select('product_id',$orderBy)->where('category_id',$category->id)
            ->orderBy($orderBy, $direction)
            ->paginate(config('system.PAGE_SIZE'));

        // Pagination的对象
        $this->dataForView['cps'] = $cps;

        // 将价格区间计算出了放到View中
        $this->_calculatePricesRange($cps->total(), $category);

        $productsId = [];
        foreach ($cps as $cp) {
            $productsId[] = $cp->product_id;
        }
        $products = Product::whereIn('id',$productsId)
            ->orderBy($orderBy=='price'?'default_price':$orderBy, $direction)
            ->get();

        $this->dataForView['products'] = $products;
        $this->dataForView['category'] = $category;

        // 总是加载Features product and promotion
        $this->dataForView['featureProducts'] = Category::LoadFeatureProducts();
        $this->dataForView['promotionProducts'] = Category::LoadPromotionProducts();

        return view('frontend.default.catalog.category',$this->dataForView);
    }

    /**
     * 找出给定目录的产品数量的最合适的价格区间数据
     * @param $productsCount
     * @param Category $category
     */
    private function _calculatePricesRange($productsCount, Category $category){

        if($productsCount == 0){
            return;
        }

        // 本目录中产品的最低价格
        $lowest_price = intval(CategoryProduct::where('category_id',$category->id)->min('price'));
        $highest_price = intval(CategoryProduct::where('category_id',$category->id)->max('price'));
        $ranges = null;

//        $lowest_price = 1000;
//        $highest_price = 10000;
//        $productsCount = 19;

        $count = $productsCount < 5 ? $productsCount : 5;
        $step = intval( ($highest_price - $lowest_price)/$count );
        if($step>1){
            $ranges = range($lowest_price,$highest_price,$step);
        }
        $this->dataForView['price_ranges'] = $ranges;
    }
}
