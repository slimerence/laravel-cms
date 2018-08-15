<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Configuration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Catalog\Product;
use App\Models\Page;

class Home extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if(config('system.SHOW_HOME_PAGE_AFTER_LOGIN_ONLY')){
            // 如果系统配置为必须登录才能浏览, 那么就先检测是否登录
            $this->middleware('auth');
        }
    }

    /**
     * 加载网站首页的视图
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->dataForView['menuName'] = 'home';

        // 加载特色产品
        $featureProducts = [];
        $products = Product::GetFeatureProducts();
        if($products){
            foreach ($products as $product) {
                $featureProducts[] = $product;
            }
        }

        $this->dataForView['featureProducts'] = Category::LoadFeatureProducts();
        $this->dataForView['promotionProducts'] = Category::LoadPromotionProducts();

        return view(
            _get_frontend_theme_prefix().'.pages.index',
            $this->dataForView
        );
    }

    /**
     * 联系我们页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact_us(Request $request){
        // 联系我们页的功能
        if($request->isMethod('post')){
            // 提交了联系我们表单
        }
        $this->dataForView['menuName'] = 'contact-us';

        // 加载网站配置信息
        $this->dataForView['configuration'] = Configuration::find(1);

        return view(
            _get_frontend_theme_prefix().'.pages.contact_us',
            $this->dataForView
        );
    }

    /**
     * 加载页面的内容
     * @param $uri
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_content($uri, Request $request){
        $page = Page::Retrieve($request);
        if($page){
            // 静态页加载成功
            $this->dataForView['menuName'] = $page->title;
            $this->dataForView['page'] = $page;

            // 添加一个判断，如果主题文件夹中有和uri同名的文件，就优先加载
            $path2 = _get_frontend_theme_prefix().'.pages';
            $filePath = resource_path()
                .'/views/frontend/'
                .str_replace('.','/',$path2)
                .'/'.$uri.'.blade.php';
            if(file_exists($filePath)){
                // 文件存在，那么按照uri的名称加载
                return view(
                    _get_frontend_theme_path('.pages.'.$uri),
                    $this->dataForView
                );
            }else{
                // 否则加载static
                return view(
                    _get_frontend_theme_path('pages.static'),
                    $this->dataForView
                );
            }
        }else{
            return view(
                _get_frontend_theme_path('pages.404'),
                $this->dataForView
            );
        }
    }

    public function my_profile($uuid=null, Request $request){
        if($uuid && $uuid == session('user_data.uuid')){
            // 表示当前的用户是正常的
            $user = User::find(session('user_data.id'));
            $this->dataForView['menuName'] = 'user';
            $this->dataForView['user'] = $user;
            $this->dataForView['vuejs_libs_required'] = ['my_profile'];
            return view(
                _get_frontend_theme_prefix().'.customers.my_profile',
                $this->dataForView
            );
        }

        if($request->isMethod('post')){
            // 客户更新Profile的申请
            if($request->get('id') == session('user_data.uuid')){
                // 正常状况
                $user = User::find(session('user_data.id'));
                $data = $request->all();
                if(isset($data['_token']))
                    unset($data['_token']);
                if(isset($data['id']))
                    unset($data['id']);

                foreach ($data as $fieldName => $fieldValue) {
                    $user->$fieldName = $fieldValue;
                }
                if($user->save()){
                    session()->flash('msg', ['content' => 'Your profile has been updated successfully!', 'status' => 'success']);
                }else{
                    session()->flash('msg', ['content' => 'System is busy, please try later!', 'status' => 'danger']);
                }
                return redirect('frontend/my_profile/'.session('user_data.uuid'));
            }
        }
    }
}
