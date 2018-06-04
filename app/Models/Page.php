<?php

namespace App\Models;

use App\Models\Widget\BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Utils\ContentTool;
use App\Models\Contract\ISupportWidget;
use PHPHtmlParser\Dom;
use Log;
use Illuminate\Http\Request;

class Page extends Model implements ISupportWidget
{
    use SoftDeletes;

    public static $TYPE_STATIC_PAGE = 1;    // 静态页面
    public static $TYPE_BLOG        = 2;    // 博客文章
    public static $TYPE_NEWS        = 3;    // 新闻文章
    public static $WIDGET_TAG       = '<span class="widget-short-code'; // 在content中搜索widget的key

    protected $fillable = [
        'layout',
        'title',
        'title_cn',
        'uri',
        'content',
        'seo_keyword',
        'seo_description',
        'feature_image',
        'type',
        'teasing',
    ];

    protected $dates = ['created_at','updated_at'];

    // 本页面包含的 Widgets
    public $widgets = [];

    public static $STATIC_PAGES_URI = [
        '/',
        '/contact-us',
        '/terms',
        '/blog',
        '/news',
    ];

    /**
     * 保存页面
     * @param $data
     * @return int
     */
    public static function Persistent($data){
        $data = ContentTool::RemoveNewLine($data);

        // URI的处理
        if(substr($data['uri'],0,1) !== '/'){
            $data['uri'] = '/'.$data['uri'];
        }

        if(!isset($data['id']) || is_null($data['id']) || empty(trim($data['id']))){
            unset($data['id']);
            $data['content'] = $data['content'];
            $page = self::create(
                $data
            );
            if($page){
                return $page->id;
            }else{
                return 0;
            }
        }else{
            $page = self::find($data['id']);
            unset($data['id']);
            foreach ($data as $field_name=>$field_value) {
                $page->$field_name = $field_value;
            }
            if($page->save()){
                return $page->id;
            }else{
                return 0;
            }
        }
    }

    /**
     * 加载页面内容的方法
     * @param Request $request
     * @return bool
     */
    public static function Retrieve(Request $request){
        $page = self::where('uri',$request->getRequestUri())->orderBy('id','desc')->first();
        if($page){
            if($page->type == ContentTool::$CONTENT_TYPE_STATIC){
                // 如果是静态内容
                return $page;
            }else{
                return $page;
            }
        }else{
            return false;
        }
    }

    /*
     * 页面加载的流程
     * 1: locateWidgets() 方法会首先扫描content, 找出所有的widget的特定 short code, 然后各自生成 HTML 代码, 保存到 page的widgets的属性数组中
     * 2: 在view的blade中, 调用 rebuildContent() 方法
     * 3: rebuildContent() 方法根据 self::$WIDGET_TAG , 打成碎片, 然后结合 widgets, 组合成最终的content, 然后返回
     */

    /**
     * 加载内容中的widgets, 然后放到 widgets 属性中
     */
    public function locateWidgets()
    {
        // 想要加载 widget, 先得有这个子字符串
        if(strpos($this->content, 'widget-short-code') === false){
            return null;
        }
        // TODO: Implement locateWidgets() method.
        $dom = new Dom;
        $dom->load($this->content);
        $shortCodeElements = $dom->find('.widget-short-code');

        if($shortCodeElements && count($shortCodeElements) > 0){
            foreach ($shortCodeElements as $key => $shortCodeElement) {
                $widgetHtml = BaseWidget::ParseVariable($shortCodeElement->text);
                if($widgetHtml){
                    $this->widgets[] = $widgetHtml;
                }
            }
            return $this->widgets;
        }
        return null;
    }

    /**
     * 重建page的真实内容HTML输出
     * @return mixed|string
     */
    public function rebuildContent()
    {
        // TODO: Implement rebuildContent() method.
        if(!empty($this->widgets)){
            // 确定本页有widgets, 那就要找出所在的index
            $parts = explode(self::$WIDGET_TAG,$this->content);
            $result = '';
            foreach ($parts as $key=>$part) {
                if($key == count($parts)-1){
                    $result .= $part.(isset($this->widgets[$key]) ? $this->widgets[$key] : null);
                }else{
                    $result .= $part.(isset($this->widgets[$key]) ? $this->widgets[$key] : null).self::$WIDGET_TAG;
                }
            }
            return $result;
        }
        return $this->content;
    }

    /**
     * 获取页面的代表图片url
     * @return string
     */
    public function getFeatureImageUrl(){
        return asset($this->feature_image);
    }

    /**
     * 获取页面对应的菜单
     * @return mixed
     */
    public function getMenuObject(){
        return Menu::where('link_to',$this->uri)->orderBy('id','asc')->first();
    }
}
