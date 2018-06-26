<?php

namespace App\Models\Widget;

use App\Models\Contract\IWidget;
use App\Models\Media;
use DB;
use Illuminate\Support\Facades\View;
use Jenssegers\Agent\Agent;

class Slider extends BaseWidget implements IWidget
{
    protected $fillable = [
        'name',
        'short_code',
        'wrapper_classes',
        'attributes_text',
        'lib',
        'need_thumbnail',
        'thumbnail_position',
        'overlay',          // navigation positions
        'interval',         // auto play interval: default 5000
        'images_per_frame'  // How many pictures per frame, default 1 image
    ];

    public $timestamps = false;
    const HOME_SLIDER_KEY = 'slider_home_page';

    /**
     * 获取Slider的图片
     * @return array
     */
    public function getImages(){
        $si = SliderImage::LoadImages($this->id);
        $images = [];
        foreach ($si as $item) {
            $images[] = $item->media;
        }
        return $images;
    }

    /**
     * 获取 SliderImage 的 Collection
     * @return mixed
     */
    public function getSliderImages(){
        return SliderImage::where('slider_id',$this->id)->orderBy('position','asc')->get();
    }

    /**
     * 删除Slider
     * @param $id
     * @return mixed
     */
    public static function Terminate($id){
        DB::beginTransaction();
        $done = self::where('id',$id)->delete();
        if($done){
            $sis = SliderImage::where('slider_id',$id)->get();
            if($sis){
                foreach ($sis as $si) {
                    $done = Media::where('id',$si->media_id)->delete();
                    if(!$done){
                        break;
                    }
                }
                if($done){
                    $done = SliderImage::where('slider_id',$id)->delete();
                }
            }
        }

        if($done){
            DB::commit();
        }else{
            DB::rollBack();
        }
        return $done;
    }

    /**
     * Slider的输出
     * @return mixed
     */
    public function outputHtml()
    {
        // TODO: Implement outputHtml() method.
        $data = [
            'sliders'=>[],
            'agentObject'=>new Agent()
        ];

        if($this->short_code == self::HOME_SLIDER_KEY){
            $data['sliders']['slider_home_page'] = $this;
            $view = View::make('frontend.'.env('frontend_theme').'.templates.sliders.'.$this->lib.'.home_slider',$data);
        }else{
            $data['sliders'][$this->short_code] = $this;
            $view = View::make('frontend.'.config('system.frontend_theme').'.templates.sliders.'.$this->lib.'.general',$data);
        }

        return $view->render();
    }
}