<?php

namespace App\Models\Widget;

use Illuminate\Database\Eloquent\Model;
use App\Models\Contract\IWidget;

class BaseWidget extends Model
{
    /**
     * 根据URI获取记录
     * @param $shortCode
     * @return mixed
     */
    public static function GetByShortCode($shortCode){
        return self::where('short_code',$shortCode)->first();
    }

    /**
     * 获取name和short_code组合的字符串
     * @param $widgetClassName
     * @return string
     */
    public function getShortCodeAsVariableName($widgetClassName){
        return $this->name.' :: '.$this->short_code.' :: '.$widgetClassName;
    }

    /**
     * @param $variable
     * @return IWidget|null
     */
    public static function ParseVariable($variable){
        $slices = explode(' :: ',$variable);

        $widget = null;
        if(count($slices) == 3){
            $className = $slices[2];
            $shortCode = $slices[1];
            switch ($className){
                case 'Slider':
                    $widget = Slider::GetByShortCode($shortCode)->outputHtml();
                    break;
                case 'Block':
                    $widget = Block::GetByShortCode($shortCode)->outputHtml();
                    break;
                case 'Gallery':
                    $widget = Gallery::GetByShortCode($shortCode)->outputHtml();
                    break;
                default:
                    break;
            }
        }
        
        return $widget;
    }
}
