<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Jenssegers\Agent\Agent;
use App\Models\Menu;
use App\Models\Configuration;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Models\Catalog\Category;
use Gloudemans\Shoppingcart\Cart;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 用来承载View模板数据的container
     *
     * @var array
     */
    public $dataForView = [
        'menuName' => null,
        'pageTitle'=>null,
        'metaKeywords'=>null,
        'metaDescription'=>null,
        'footer'=>null,             // 页脚的Block
        'floatingBox'=>null,        // 页面浮动的Block
        'the_referer'=>null,        // 跟踪客户的referer
        // 和电商相关的部分
        'categoriesTree'=>[],
        'categoriesNav'=>[],
        'cart'=>null
    ];

    // 网站的配置信息对象
    public $siteConfig = null;

    /**
     * 构造函数
     * Controller constructor.
     */
    public function __construct()
    {
        $this->dataForView['agentObject'] = new Agent();
        $this->dataForView['rootMenus'] = Menu::getRootMenus();
        $this->siteConfig = Configuration::find(1);
        $this->dataForView['siteConfig'] = $this->siteConfig;

        // 和电商相关
        if(env('activate_ecommerce',false)){
            $categoriesTree = Category::LoadFirstLevelCategoriesInMenu();
            $this->dataForView['categoriesTree'] = $categoriesTree;
            $data = [];
            foreach ($categoriesTree as $category) {
                $subs = $category->loadForNav();
                if(count($subs)>0){
                    $data[] = $subs;
                }
            }
            $this->dataForView['categoriesNav'] = $data;
            $this->_createCart();
        }
    }

    /**
     * 把用户信息保存到session中
     * @param User $user
     */
    public function _saveUserInSession(User $user){
        Session::put('user_data',[
            'id'=>$user->id,
            'uuid'=>$user->uuid,
            'name'=>$user->name,
            'email'=>$user->email,
            'role'=>$user->role,
            'group'=>$user->group_id,
            'status'=>$user->status
        ]);
    }

    /**
     * 为其他的控制器类获取购物车实例提供的方法
     * @return \Gloudemans\Shoppingcart\Cart
     */
    public function getCart(){
        if(is_null($this->dataForView['cart'])){
            $this->dataForView['cart'] = $this->_createCart();
        }
        return $this->dataForView['cart'];
    }

    /**
     * 计算购物车中所有货品的重量
     * @return float|int
     */
    public function getTotalWeightInCart(){
        $content = $this->getCart()->content();
        $totalWeight = 0;
        foreach ($content as $item) {
            $totalWeight += isset($item->options['weight']) ? floatval($item->options['weight'])*$item->qty : 0;
        }
        return $totalWeight;
    }

    /**
     * Get an instance of the cart.
     *
     * @return \Gloudemans\Shoppingcart\Cart
     */
    private function _createCart()
    {
        $session = app()->make('session');
        $events = app()->make('events');
        return new Cart($session, $events);
    }
}
