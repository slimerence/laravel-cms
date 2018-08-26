<?php

namespace App\Models\Widget;

use Illuminate\Database\Eloquent\Model;
use App\Models\Media;
use App\Models\Utils\MediaTool;

class SliderImage extends Model
{
    protected $fillable = [
        'position',
        'slider_id',
        'media_id',
        'html_tag',
        'classes_name',
        'extra_html',
        'link_to',
        'caption',
        'animate_class',
        'animate_class_out',// 退出时
    ];

    /**
     * 根据指定的 slider id 加载所有的关联图片
     * @param $sliderId
     * @return mixed
     */
    public static function LoadImages($sliderId){
        $sliderImages = self::where('slider_id',$sliderId)->orderBy('position','asc')->get();
        return $sliderImages;
    }

    public function media(){
        return $this->belongsTo(Media::class);
    }

    /**
     * 删除 Slider Image 记录
     * @param $id
     * @return mixed
     */
    public static function Terminate($id){
        self::where('id',$id)->delete();
        return Media::where('target_id',$id)
            ->where('for',MediaTool::$FOR_GALLERY)
            ->delete();
    }

    /**
     * Return all support animations for slider
     * @return array
     */
    public static function GetAnimateEffects(){
        return [
            'bounce','flash','pulse','rubberBand','shake','headShake','swing','tada','wobble','jello',
            'bounceIn','bounceInDown','bounceInLeft','bounceInRight','bounceInUp','bounceOut','bounceOutDown',
            'bounceOutLeft','bounceOutRight','bounceOutUp','fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig',
            'fadeInUp','fadeInUpBig','fadeOut','fadeOutDown','fadeOutDownBig','fadeOutLeft','fadeOutLeftBig','fadeOutRight','fadeOutRightBig','fadeOutUp',
            'fadeOutUpBig','flipInX','flipInY','flipOutX','flipOutY','lightSpeedIn','lightSpeedOut','rotateIn','rotateInDownLeft','rotateInDownRight',
            'rotateInUpLeft','rotateInUpRight','rotateOut','rotateOutDownLeft','rotateOutDownRight','rotateOutUpLeft','rotateOutUpRight','hinge','jackInTheBox',
            'rollIn','rollOut','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp','zoomOut','zoomOutDown','zoomOutLeft',
            'zoomOutRight','zoomOutUp','slideInDown','slideInLeft','slideInRight','slideInUp','slideOutDown','slideOutLeft','slideOutRight','slideOutUp',
        ];
    }
}
