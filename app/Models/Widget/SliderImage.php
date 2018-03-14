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
    ];

    /**
     * 根据指定的 slider id 加载所有的关联图片
     * @param $sliderId
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
}
