<?php

namespace App\Http\Controllers\Api;

use App\Models\Catalog\Category;
use App\Models\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Brand;

class Brands extends Controller
{
    /**
     * 加载品牌的数据
     * @param null $categoryId
     * @return string
     */
    public function load_all($categoryId = null){
        $brands = Brand::select('id as key','name as label')->orderBy('name','asc')->get();
        $categoryBrandsArray = [];
        if($categoryId){
            $category = Category::select('brands')->where('id',$categoryId)->first();
            if($category && $category->brands){
                $categoryBrandsArray = $category->brands;
            }
        }
        return JsonBuilder::Success(['brands'=>$brands,'categoryBrands'=>$categoryBrandsArray]);
    }

    /**
     * 根据品牌名称加载
     * @param Request $request
     * @return string
     */
    public function load_by_name(Request $request){
        $name = $request->get('name');
        if($name){
            $brand = Brand::where('name',$name)->orderBy('id','asc')->first();
            if($brand){
                return JsonBuilder::Success(['brandImage'=>$brand->getImageUrl(),'brand'=>$brand]);
            }
        }
        return JsonBuilder::Error();
    }
}
