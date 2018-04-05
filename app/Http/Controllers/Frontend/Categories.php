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
        // 加载排序条件
        $orderBy = $request->has('orderBy') ? $request->get('orderBy') : 'position';
        $direction = $request->has('dir') ? $request->get('dir') : 'asc';

        $category = Category::where('uri',$uri)->first();
        $cps = CategoryProduct::select('product_id',$orderBy)->where('category_id',$category->id)
            ->orderBy($orderBy, $direction)
            ->paginate(config('system.PAGE_SIZE'));
        $productsId = [];
        foreach ($cps as $cp) {
            $productsId[] = $cp->product_id;
        }
        $products = Product::whereIn('id',$productsId)->get();

        $this->dataForView['products'] = $products;
        return view('frontend.default.catalog.category',$this->dataForView);
    }
}
