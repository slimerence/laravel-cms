<?php

namespace App\Models\Widget;

use App\Models\Contract\IWidget;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\View;

class Gallery extends BaseWidget implements IWidget
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'short_code',
        'wrapper_classes',
        'lib',
        'attributes_text',
        'images_per_row',
    ];

    public function galleryItems(){
        return $this->hasMany(GalleryItem::class);
    }

    /**
     * Gallery的最终输出
     * @return mixed
     */
    public function outputHtml()
    {
        // TODO: Implement outputHtml() method.
        $data = [
            'galleries'=>[],
            'agentObject'=>new Agent()
        ];

        $data['galleries'][$this->short_code] = $this;
        $view = View::make('frontend.'.config('system.frontend_theme').'.templates.galleries.'.$this->lib.'.general',$data);

        return $view->render();
    }
}
