<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Widget\Slider;
use App\Models\Widget\Block;
use App\Models\Utils\JsonBuilder;
use App\Models\Widget\Sidebar;
use App\Models\Widget\AdvanceForm;
use App\Models\Widget\Gallery;

class ShortCodes extends Controller
{
    /**
     * 给所见即所得编辑器加载Variables
     * @return string
     */
    public function load_short_codes(){
        $sliders = Slider::select('name','short_code')->orderBy('name','asc')->get();
        $result = [];
        foreach ($sliders as $slider) {
            $result[] = $slider->getShortCodeAsVariableName('Slider');
        }

        $blocks = Block::select('name','short_code')->where('type',Block::$TYPE_GENERAL)->orderBy('name','asc')->get();
        foreach ($blocks as $block) {
            $result[] = $block->getShortCodeAsVariableName('Block');
        }

        $galleries = Gallery::select('name','short_code')->orderBy('name','asc')->get();
        foreach ($galleries as $gallery) {
            $result[] = $gallery->getShortCodeAsVariableName('Gallery');
        }

        return JsonBuilder::Success($result);
    }

    /**
     * 检查给定的 Short Code 是否在Widgets中存在了
     * @param Request $request
     * @return string
     */
    public function is_uri_unique(Request $request){
        $exist = Slider::GetByShortCode($request->get('uri'));

        if(!$exist){
            $exist = Sidebar::GetByShortCode($request->get('uri'));
        }

        if (!$exist){
            $exist = AdvanceForm::GetByShortCode($request->get('uri'));
        }
        return $exist ? JsonBuilder::Error() : JsonBuilder::Success();
    }
}
