<?php

namespace App\Http\Controllers\Api;

use App\Models\Utils\MediaTool;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Utils\JsonBuilder;
use App\Models\Media;
use App\Models\Catalog\Product;

class Medias extends Controller
{
    /**
     * 保存提交的Attachment
     * 收到的数据中,还包含了index的值, 这个值要传回客户端
     * @param Request $request
     * @return string
     */
    public function upload_attachment_ajax(Request $request){
        // 提交的信息 Content-Disposition: form-data; name="image"; filename="9.jpeg"
        $path = '';
        if($request->file('file'))
            $path = $request->file('file')
                ->store(_buildUploadFolderPath(),'public');
        elseif ($request->file('image'))
            $path = $request->file('image')
                ->store(_buildUploadFolderPath(),'public');

        return [
            'index'=>$request->get('index'),
            'path'=>_buildFrontendAssertPath($path)
        ];
    }
    
    /**
     * 根据给定的产品UUID加载图片的方法
     * @param Request $request
     * @return string
     */
    public function load_all(Request $request){
        $images = Media::where('type',MediaTool::$TYPE_IMAGE)->get();
        if($images){
            $data = [];
            foreach ($images as $key=>$media) {
                $data[] = [
                    'thumb'=>$media->url,
                    'url'=>$media->url,
                    'id'=>'img'.$key,
                    'title'=>$media->alt.' '.$key,
                ];
            }
            return JsonBuilder::Success($data);
        }else{
            return JsonBuilder::Error();
        }
    }

    /**
     * 保存设备的图片
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function upload_ajax(Request $request){
        // 提交的信息 Content-Disposition: form-data; name="image"; filename="9.jpeg"

        $path = '';
        $storagePath = _buildUploadFolderPath();
        if($request->hasFile('file')){
            $uploaded = $request->file('file');
            if(is_array($uploaded)){
                $result = [];
                foreach ($uploaded as $key=>$file) {
                    $path = $file->store($storagePath,'public');
                    $result['file-'.$key] = [
                        'id'=>str_random(16),
                        'url'=>_buildFrontendAssertPath($path)
                    ];
                }
                return $result;
            }else{
                $path = $request->file('file')
                    ->store($storagePath,'public');
            }
        }
        elseif ($request->file('image'))
            $path = $request->file('image')
                ->store($storagePath,'public');

        if(!empty($path)){
            Media::Persistent(0,MediaTool::$TYPE_IMAGE,_buildFrontendAssertPath($path));
        }

        return [
            'id'=>str_random(16),
            'url'=>_buildFrontendAssertPath($path)
        ];
    }



    /**
     * 根据给定MEDIA的ID删除记录
     * @param Request $request
     * @return string
     */
    public function delete_ajax(Request $request){
        if(Media::Terminate($request->get('id'))){
            return JsonBuilder::Success();
        }else{
            return JsonBuilder::Error();
        }
    }

    /**
     * 根据给定的产品UUID加载图片的方法
     * @param Request $request
     * @return string
     */
    public function load_by_product(Request $request){
        $images = Product::GetAllImages($request->get('id'));
        if($images){
            return JsonBuilder::Success($images->toArray());
        }else{
            return JsonBuilder::Error();
        }
    }
}
