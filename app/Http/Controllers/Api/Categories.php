<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use App\Models\Utils\JsonBuilder;

class Categories extends Controller
{
    /**
     * 远端请求加载目录树, 返回json数据
     * @return string
     */
    public function tree(){
        $categoriesTreeArray = Category::Tree()->toArray();
        return JsonBuilder::Success($categoriesTreeArray);
    }

    /**
     * 删除目录的方法
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function delete(Request $request){
        if($request->get('category') && Category::Terminate($request->get('category'))){
            return JsonBuilder::Success();
        }else{
            return JsonBuilder::Error();
        }
    }

    /**
     * 为后台提供的保存分类目录信息的方法
     * @param Request $request
     * @return string
     */
    public function save(Request $request){
        $categoryData = $request->get('category');
        if($categoryId = Category::Persistent($categoryData)){
            return JsonBuilder::Success([
                'msg'=>$categoryId
            ]);
        }else{
            return JsonBuilder::Error();
        }
    }

    public function load_nav($uuid){
        return JsonBuilder::Success(Category::GetByUuid($uuid)->loadForNav());
    }
}
