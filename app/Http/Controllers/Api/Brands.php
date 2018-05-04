<?php

namespace App\Http\Controllers\Api;

use App\Models\Catalog\Category;
use App\Models\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Brand;
use App\Models\Catalog\BrandSerial;

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
                return JsonBuilder::Success(
                    [
                        'brandImage'=>$brand->getImageUrl(),
                        'brand'=>$brand,
                        'brandSerials'=>$brand->serials
                    ]
                );
            }
        }
        return JsonBuilder::Error();
    }

    /**
     * 保存品牌Serial的数据
     * @param Request $request
     * @return string
     */
    public function save_serial(Request $request){
        $data = $request->get('serialForm');
        if(empty($data['id'])){
            if(BrandSerial::create($data)){
                return JsonBuilder::Success();
            }else{
                return JsonBuilder::Error();
            }
        }else{
            $id = $data['id'];
            unset($data['id']);
            unset($data['selectedBrandName']);
            if(BrandSerial::where('id',$id)->update($data)){
                return JsonBuilder::Success();
            }else{
                return JsonBuilder::Error();
            }
        }
    }

    /**
     * 加载品牌Serial的数据
     * @param Request $request
     * @return string
     */
    public function get_serial(Request $request){
        $id = $request->get('id');
        if($id){
            $brandSerial = BrandSerial::find($id);
            if($brandSerial){
                return JsonBuilder::Success(['serial'=>$brandSerial,'brand_name'=>$brandSerial->brand->name]);
            }
        }
        return JsonBuilder::Error();
    }

    /**
     * 加载品牌Serial的数据
     * @param Request $request
     * @return string
     */
    public function delete_serial(Request $request){
        $id = $request->get('id');
        if($id){
            if(BrandSerial::where('id',$id)->delete()){
                return JsonBuilder::Success();
            }
        }
        return JsonBuilder::Error();
    }
}
