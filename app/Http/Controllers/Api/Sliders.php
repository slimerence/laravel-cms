<?php

namespace App\Http\Controllers\Api;

use App\Models\Widget\AdvanceForm;
use App\Models\Widget\Sidebar;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Widget\Slider;
use App\Models\Utils\JsonBuilder;
use App\Models\Widget\SliderImage;
use App\Models\Media;
use App\Models\Utils\MediaTool;
use App\Models\Widget\Block;

class Sliders extends Controller
{
    /**
     * 加载已经存在的Sliders
     * @return string
     */
    public function load_all(){
        $sliders = Slider::orderBy('id','desc')->get();
        return JsonBuilder::Success($sliders->toArray());
    }

    /**
     * 保存Slider的操作
     * @param Request $request
     * @return string
     */
    public function save(Request $request){
        $data = $request->get('slider');
        $sliderId = $data['id'];
        unset($data['id']);
        $slider = null;

        if(!empty($sliderId)){
            // Update
            Slider::where('id',$sliderId)->update($data);
            return JsonBuilder::Success();
        }else{
            $slider = Slider::create($data);
            if($slider){
                return JsonBuilder::Success(['newId'=>$slider->id]);
            }else{
                return JsonBuilder::Error();
            }
        }
    }

    /**
     * 保存Slider Image
     * @param Request $request
     * @return string
     */
    public function save_slider_image(Request $request){
        $imageSliderData = $request->get('sliderImage');
        $sliderId = $request->get('sliderId');
        $mediaUri = $request->get('mediaUri');
        if (is_array($mediaUri)){
            $mediaUri = isset($mediaUri['url']) ? $mediaUri['url'] : null;
        }

        $imageSliderData['slider_id'] = $sliderId;
        $imageSliderId = $imageSliderData['id'];
        unset($imageSliderData['id']);

        if(empty($imageSliderId)){
            // 创建新的
            $sliderImage = SliderImage::create($imageSliderData);
            if($sliderImage){
                // 保存成功
                if(!empty($mediaUri)){
                    // 表示提交了图片的URI
                    $media = Media::Persistent($sliderImage->id,MediaTool::$TYPE_IMAGE,$mediaUri);
                    if($media){
                        // 图片保存成功了
                        $sliderImage->media_id = $media->id;
                        $sliderImage->save();
                    }
                }
                return JsonBuilder::Success(['newSliderImageId'=>$sliderImage->id]);
            }else{
                return JsonBuilder::Error();
            }
        }else{
            unset($imageSliderData['media']);
            SliderImage::where('id',$imageSliderId)->update($imageSliderData);
            if(true){
                // 检查是否已经关联的图片
                $sliderImage = SliderImage::find($imageSliderId);
                if($sliderImage->media_id == 0){
                    // 表示现在还没有关联图片
                    if(!empty($mediaUri)){
                        // 表示提交了图片的URI
                        $media = Media::Persistent($sliderImage->id,MediaTool::$TYPE_IMAGE,$mediaUri);
                        if($media){
                            // 图片保存成功了
                            $sliderImage->media_id = $media->id;
                            $sliderImage->save();
                        }
                    }
                }else{
                    // 表示已经关联了图片,那么需要更新一下
//                    $sliderImage->media->url = $mediaUri;
//                    $sliderImage->media->save();
                    if(!is_null($mediaUri)){
                        Media::where('id',$sliderImage->media_id)->update(['url'=>$mediaUri]);
                    }
                }

                return JsonBuilder::Success();
            }else{
                return JsonBuilder::Error();
            }
        }
    }

    /**
     * 加载 Slider 数据
     * @param $id
     * @return string
     */
    public function load_slider($id){
        $slider = Slider::select('id','name','short_code','wrapper_classes',
            'attributes_text','overlay','interval','images_per_frame','lib','need_thumbnail','thumbnail_position')->where('id',$id)->first();
        $sis = SliderImage::LoadImages($slider->id);
        foreach ($sis as $si) {
            $si->media;
        }
        return JsonBuilder::Success([
            'slider'=>$slider,
            'sliderImages'=>$sis
        ]);
    }

    public function load_slider_images($sliderId){
        $slider = Slider::find($sliderId);
        $si = SliderImage::LoadImages($sliderId);
        return JsonBuilder::Success($si);
    }

    /**
     * 后台删除Slider Widget的操作
     * @param Request $request
     * @return string
     */
    public function delete(Request $request){
        $userUuid = $request->get('user');
        $sliderId = $request->get('slider');
        $deleted = false;
        if(User::GetByUuid($userUuid)){
            $deleted = Slider::Terminate($sliderId);
        }
        return $deleted ? JsonBuilder::Success():JsonBuilder::Error();
    }
}

