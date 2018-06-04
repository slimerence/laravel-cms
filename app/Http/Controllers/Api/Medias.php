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
     * 加载所有已上传的图片的方法
     * @param Request $request
     * @return string
     */
    public function load_all(Request $request){
        $images = Media::where('type',MediaTool::$TYPE_IMAGE)
            ->orderBy('id','desc')->get();
        if($images){
            $data = [];
            foreach ($images as $key=>$media) {
                $data[] = [
                    'thumb'=>$media->url,
                    'url'=>$media->url,
                    'id'=>$media->id,
                    'title'=>$media->alt
                ];
            }
            echo json_encode($data, JSON_PRETTY_PRINT);
        }else{
            return JsonBuilder::Error();
        }
    }

    /**
     * 加载所有已上传的文件的方法
     * @param Request $request
     * @return string
     */
    public function load_all_files(Request $request){
        $images = Media::where('type','<>',MediaTool::$TYPE_IMAGE)
            ->orderBy('id','desc')->get();
        if($images){
            $data = [];
            foreach ($images as $key=>$media) {
                $data[] = [
                    'size'=>$media->size.'K',
                    'name'=>$media->alt,
                    'url'=>$media->url,
                    'id'=>$media->id,
                    'title'=>$media->alt
                ];
            }
            echo json_encode($data, JSON_PRETTY_PRINT);
        }else{
            return JsonBuilder::Error();
        }
    }

    /**
     * 保存上传的图片, 注意提交的file字段的名字必须是file或者image
     * @param Request $request
     * @return array
     */
    public function upload_ajax(Request $request){
        // 提交的信息 Content-Disposition: form-data; name="image"; filename="9.jpeg"
        $path = '';
        $storagePath = _buildUploadFolderPath();
        // 获取上传的内容是针对哪类应用的，比如产品，目录，gallery等
        $mediaFor = $request->has('for') ? $request->get('for') : MediaTool::$FOR_GENERAL;
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
                    if(!empty($path)){
                        // 保存到数据库中
                        Media::Persistent(
                            0,
                            MediaTool::$TYPE_IMAGE,
                            _buildFrontendAssertPath($path),
                            $file->getClientOriginalName(),
                            $mediaFor
                        );
                    }
                }
                return $result;
            }else{
                if($uploaded){
                    $path = $uploaded->store($storagePath,'public');
                    // 保存到数据库中
                    Media::Persistent(
                        0,
                        MediaTool::$TYPE_IMAGE,
                        _buildFrontendAssertPath($path),
                        $uploaded->getClientOriginalName(),
                        $mediaFor
                    );
                }

            }
        }
        elseif ($request->hasFile('image')){
            $uploaded = $request->file('image');
            if($uploaded){
                $path = $uploaded->store($storagePath,'public');
                // 保存到数据库中
                Media::Persistent(
                    0,
                    MediaTool::$TYPE_IMAGE,
                    _buildFrontendAssertPath($path),
                    $uploaded->getClientOriginalName(),
                    $mediaFor
                );
            }
        }
        return [
            'id'=>str_random(16),
            'url'=>_buildFrontendAssertPath($path)
        ];
    }

    /**
     * 保存上传的文件, 注意提交的file字段的名字必须是file或者image
     * @param Request $request
     * @return array
     */
    public function upload_file_ajax(Request $request){
        $path = '';
        $storagePath = _buildUploadFolderPath();
        // 获取上传的内容是针对哪类应用的，比如产品，目录，gallery等
        $mediaFor = $request->has('for') ? $request->get('for') : MediaTool::$FOR_GENERAL;
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
                    if(!empty($path)){
                        // 保存到数据库中
                        Media::Persistent(
                            0,
                            MediaTool::GuessFileTypeByExtensionName($file->extension()),
                            _buildFrontendAssertPath($path),
                            $file->getClientOriginalName(),
                            $mediaFor
                        );
                    }
                }
                return $result;
            }else{
                if($uploaded){
                    $path = $uploaded->store($storagePath,'public');
                    // 保存到数据库中
                    Media::Persistent(
                        0,
                        MediaTool::GuessFileTypeByExtensionName($uploaded->extension()),
                        _buildFrontendAssertPath($path),
                        $uploaded->getClientOriginalName(),
                        $mediaFor
                    );
                }

            }
        }
        elseif ($request->hasFile('image')){
            $uploaded = $request->file('image');
            if($uploaded){
                $path = $uploaded->store($storagePath,'public');
                // 保存到数据库中
                Media::Persistent(
                    0,
                    MediaTool::GuessFileTypeByExtensionName($uploaded->extension()),
                    _buildFrontendAssertPath($path),
                    $uploaded->getClientOriginalName(),
                    $mediaFor
                );
            }
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
