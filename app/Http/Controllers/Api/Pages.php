<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Utils\JsonBuilder;

class Pages extends Controller
{
    /**
     * 为后台提供的保存 Page 信息的方法
     * @param Request $request
     * @return string
     */
    public function save(Request $request){
        $pageData = $request->get('page');
        if($pageId = Page::Persistent($pageData)){
            return JsonBuilder::Success([
                'msg'=>$pageId
            ]);
        }else{
            return JsonBuilder::Error();
        }
    }

    /**
     * 判断是否uri已经存在了
     * @param Request $request
     * @return string
     */
    public function is_uri_unique(Request $request){
        // 检查给定的ID
        $id = $request->get('id');
        if(empty($id)){
            // 这个相当于新添加页面时的验证
            $page = Page::where('uri',$request->get('uri'))->first();
        }else{
            // 这个相当于修改页面时的验证
            $page = Page::where('uri',$request->get('uri'))->where('id','<>',$id)->first();
        }

        return $page ? JsonBuilder::Error() : JsonBuilder::Success();
    }

    /**
     * 搜索: 搜索的目标是seo keyword 字段
     * @param Request $request
     * @return string
     */
    public function search_ajax(Request $request){
        $q = $request->get('q');
        $result = [];
        // 搜索Page的title
        $pages = Page::select('uri','title')->where('seo_keyword','like','%'.$q.'%')->limit(20)->get();
        if (count($pages)){
            foreach ($pages as $page){
                $result[] = [
                    'value' =>$page->title,
                    'uri'   =>$page->uri=='/' ? $page->uri : '/page'.$page->uri
                ];
            }
        }

        return count($result)>0 ? JsonBuilder::Success(['result'=>$result]) : JsonBuilder::Error();
    }
}
