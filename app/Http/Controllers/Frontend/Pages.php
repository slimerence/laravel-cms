<?php

namespace App\Http\Controllers\Frontend;

use App\Events\Contact\LeadReceived;
use App\Events\Page\Content\StartLoading;
use App\Models\Configuration;
use App\Models\Lead;
use App\Models\Utils\ContentTool;
use App\Models\Utils\JsonBuilder;
use App\Models\Widget\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Widget\Block;
use App\Models\Catalog\Category;

class Pages extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // 加载专有的Block
        $dedicateBlocks = Block::LoadDedicateBlocks();
        $this->dataForView = array_merge($this->dataForView, $dedicateBlocks);
    }

    /**
     * 加载首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $page = Page::where('uri','/')->first();
        $this->dataForView['page'] = $page;

        $this->dataForView['pageTitle'] = app()->getLocale()=='cn' ? $page->title_cn : $page->title;
        $this->dataForView['metaKeywords'] = $page->seo_keyword;
        $this->dataForView['metaDescription'] = $page->seo_description;

        // 尝试加载首页使用的Slider
        $this->dataForView['homeSlider'] = Slider::where('short_code','slider_home_page')->first();

        // 加载特色产品和促销产品
        $this->dataForView['featureProducts'] = Category::LoadFeatureProducts();
        $this->dataForView['promotionProducts'] = Category::LoadPromotionProducts();

        $posts = Page::where('type',Page::$TYPE_BLOG)->orderBy('id','asc')->paginate(20);
        $this->dataForView['posts'] = $posts;

        event(new StartLoading($page,$this->dataForView));

        return view($this->_getPageViewTemplate($page),$this->dataForView);
    }

    /**
     * 加载静态页内容
     * @param $pageUri
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($pageUri, Request $request){
        $page = Page::where('uri',$pageUri)->orWhere('uri', '/'.$pageUri)->first();

        if(!$page){
            // 404 Error
            return view('frontend.'.config('system.frontend_theme').'.pages.404', $this->dataForView);
        }

        $this->dataForView['page'] = $page;

        $this->dataForView['pageTitle'] = app()->getLocale()=='cn' ? $page->title_cn : $page->title;
        $this->dataForView['metaKeywords'] = $page->seo_keyword;
        $this->dataForView['metaDescription'] = $page->seo_description;

        event(new StartLoading($page,$this->dataForView));
        return view($this->_getPageViewTemplate($page), $this->dataForView);
    }

    /**
     * 联系我们页面专属
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact_us(){
        $this->dataForView['pageTitle'] = trans('general.menu_contact');
        $this->dataForView['metaKeywords'] = trans('general.menu_contact');
        $this->dataForView['metaDescription'] = trans('general.menu_contact');
        $this->dataForView['config'] = Configuration::find(1);

        if(env('output_leads_to_contact_us', false)){
            $this->dataForView['leads'] = Lead::orderBy('id','desc')->limit(20)->get();
        }

        return view(_get_frontend_theme_path('pages.contact_us'), $this->dataForView);
    }

    /**
     * 保存联系我们数据
     * @param Request $request
     * @return string
     */
    public function contact_us_handler(Request $request){
        $leadData = $request->get('lead');
        if($lead = Lead::Persistent($leadData)){
            // 通知网站管理员和用户
            event(new LeadReceived($lead, $this->siteConfig->contact_email));
            return JsonBuilder::Success();
        }
        return JsonBuilder::Error();
    }

    /**
     * Terms 页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function terms(){
        $page = Page::where('uri','terms')->orWhere('uri', '/terms')->first();

        if(!$page){
            // 404 Error
            return response()->view('frontend.'.config('system.frontend_theme').'.pages.404', $this->dataForView,404);
        }

        $this->dataForView['page'] = $page;

        if($page){
            event(new StartLoading($page,$this->dataForView));
        }

        $this->dataForView['pageTitle'] = 'Terms';
        $this->dataForView['metaKeywords'] = 'Terms and condition';
        $this->dataForView['metaDescription'] = 'Terms and condition';
        return view($this->_getPageViewTemplate($page), $this->dataForView);
    }

    /**
     * Blog 列表页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blog(Request $request){
        $posts = Page::where('type',Page::$TYPE_BLOG)->orderBy('id','asc')->paginate(20);
        $this->dataForView['posts'] = $posts;
        $this->dataForView['pageTitle'] = 'Blog';
        $this->dataForView['metaKeywords'] = 'Blog';
        $this->dataForView['metaDescription'] = 'Blog';
        return view(_get_frontend_theme_path('templates.blog_list'), $this->dataForView);
    }

    /**
     * 博客页面
     * @param $uri
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blog_view($uri, Request $request){
        $blog = Page::where('type',Page::$TYPE_BLOG)
            ->where('uri','/blog/'.$uri)
            ->orderBy('id','asc')
            ->first();

        $this->dataForView['blog'] = $blog;
        $this->dataForView['pageTitle'] = $blog->title;
        $this->dataForView['metaKeywords'] = $blog->seo_keyword;
        $this->dataForView['metaDescription'] = $blog->seo_description;
        return view(_get_frontend_theme_path('templates.blog_view'), $this->dataForView);
    }

    public function blog_search(Request $request){
        $q = $request->get('name');
        $posts = Page::where('type',Page::$TYPE_BLOG)
            ->where('seo_keyword','like','%'.$q.'%')
            ->orderBy('id','asc')
            ->paginate(20);
        $this->dataForView['posts'] = $posts;
        $this->dataForView['pageTitle'] = 'Blog';
        $this->dataForView['metaKeywords'] = 'Blog';
        $this->dataForView['metaDescription'] = 'Blog';
        return view(_get_frontend_theme_path('templates.blog_list'), $this->dataForView);
    }

    /**
     * News 页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function news(Request $request){
        $news = Page::where('type',Page::$TYPE_NEWS)->orderBy('updated_at','desc')->paginate(20);
        $this->dataForView['news'] = $news;
        $this->dataForView['pageTitle'] = 'News Room';
        $this->dataForView['metaKeywords'] = 'News Room';
        $this->dataForView['metaDescription'] = 'News Room';
        return view(_get_frontend_theme_path('templates.news_list'), $this->dataForView);
    }

    /**
     * 根据给定的Page返回模板文件名
     * @param Page $page
     * @return string
     */
    private function _getPageViewTemplate(Page $page){
        // Home page, Contact page, List view page
//        $template = 'frontend.'.config('system.frontend_theme').'.';

        if(in_array($page->uri, Page::$STATIC_PAGES_URI)){
            // 是系统的几个保留页面URI
            switch ($page->uri){
                case '/':
                    $template = 'pages.home';
                    break;
                case '/contact-us':
                    $template = 'pages.contact_us';
                    break;
                case '/terms':
                    $template = 'pages.terms';
                    break;
                default:
                    $template = 'pages.404';
                    break;
            }
        }else{
            // 动态页
            switch ($page->layout){
                case ContentTool::$LAYOUT_ONE_COLUMN:
                    $template = 'templates.one_column';
                    break;
                case ContentTool::$LAYOUT_TWO_COLUMNS_LEFT:
                    $template = 'templates.two_columns_left';
                    break;
                case ContentTool::$LAYOUT_TWO_COLUMNS_RIGHT:
                    $template = 'templates.two_columns_right';
                    break;
                case ContentTool::$LAYOUT_THREE_COLUMNS:
                    $template = 'templates.three_columns';
                    break;
                default:
                    $template = 'pages.404';
                    break;
            }
        }

        return _get_frontend_theme_path($template);
    }
}
