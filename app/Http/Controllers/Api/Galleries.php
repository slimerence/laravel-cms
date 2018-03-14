<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Widget\Gallery;
use App\Models\Widget\GalleryItem;
use App\Models\Utils\JsonBuilder;
use App\Models\Media;
use App\Models\Utils\MediaTool;

class Galleries extends Controller
{
    /**
     * 后台加载所有的gallery
     * @return string
     */
    public function load_all(){
        $galleries = Gallery::orderBy('id','desc')->get();
        return JsonBuilder::Success($galleries->toArray());
    }

    /**
     * 保存 Gallery 的操作
     * @param Request $request
     * @return string
     */
    public function save(Request $request){
        $data = $request->get('gallery');
        $sliderId = $data['id'];
        unset($data['id']);
        $slider = null;

        if(!empty($sliderId)){
            // Update
            $updated = Gallery::where('id',$sliderId)->update($data);
            return $updated ? JsonBuilder::Success() : JsonBuilder::Error();
        }else{
            $slider = Gallery::create($data);
            if($slider){
                return JsonBuilder::Success(['newId'=>$slider->id]);
            }else{
                return JsonBuilder::Error();
            }
        }
    }

    /**
     * 加载指定 Id 的 Gallery
     * @param $id
     * @return string
     */
    public function load_gallery($id){
        $gallery = Gallery::find($id);
        $sis = GalleryItem::LoadImages($gallery->id);
        foreach ($sis as $si) {
            $si->media;
        }
        return JsonBuilder::Success([
            'gallery'=>$gallery,
            'galleryImages'=>$sis
        ]);
    }

    /**
     * 保存画廊图片
     * @param Request $request
     * @return string
     */
    public function save_gallery_image(Request $request)
    {
        $galleryImageData = $request->get('galleryImage');
        $galleryId = $request->get('galleryId');
        $mediaUri = $request->get('mediaUri');
        if (is_array($mediaUri)){
            $mediaUri = isset($mediaUri['url']) ? $mediaUri['url'] : null;
        }

        $galleryImageData['gallery_id'] = $galleryId;
        $imageSliderId = $galleryImageData['id'];
        unset($galleryImageData['id']);

        if(empty($imageSliderId)){
            // 创建新的
            $galleryItem = GalleryItem::create($galleryImageData);
            if($galleryItem){
                // 保存成功
                if(!empty($mediaUri)){
                    // 表示提交了图片的URI
                    $media = Media::Persistent($galleryItem->id,
                        MediaTool::$TYPE_IMAGE,
                        $mediaUri,
                        $galleryItem->caption,
                        MediaTool::$FOR_GALLERY
                    );
                    if($media){
                        // 图片保存成功了
                        $galleryItem->media_id = $media->id;
                        $galleryItem->save();
                    }
                }
                return JsonBuilder::Success(['newGalleryImageId'=>$galleryItem->id]);
            }else{
                return JsonBuilder::Error();
            }
        }else{
            unset($galleryImageData['media']);
            GalleryItem::where('id',$imageSliderId)->update($galleryImageData);
            if(true){
                // 检查是否已经关联的图片
                $galleryItem = GalleryItem::find($imageSliderId);
                if($galleryItem->media_id == 0){
                    // 表示现在还没有关联图片
                    if(!empty($mediaUri)){
                        // 表示提交了图片的URI
                        $media = Media::Persistent($galleryItem->id,MediaTool::$TYPE_IMAGE,$mediaUri);
                        if($media){
                            // 图片保存成功了
                            $galleryItem->media_id = $media->id;
                            $galleryItem->save();
                        }
                    }
                }else{
                    // 表示已经关联了图片,那么需要更新一下
//                    $sliderImage->media->url = $mediaUri;
//                    $sliderImage->media->save();
                    if(!is_null($mediaUri)){
                        Media::where('id',$galleryItem->media_id)->update(['url'=>$mediaUri]);
                    }
                }

                return JsonBuilder::Success();
            }else{
                return JsonBuilder::Error();
            }
        }
    }

    /**
     * 删除一个Gallery Item的操作
     * @param $itemId
     * @return string
     */
    public function delete_gallery_item($itemId){
        GalleryItem::Terminate($itemId);
        return JsonBuilder::Success();
    }
}
