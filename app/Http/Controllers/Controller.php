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
        'the_referer'=>null         // 跟踪客户的referer
    ];

    /**
     * 构造函数
     * Controller constructor.
     */
    public function __construct()
    {
        $this->dataForView['agentObject'] = new Agent();
        $this->dataForView['rootMenus'] = Menu::getRootMenus();
        $categoriesTree = Category::LoadFirstLevelCategoriesInMenu();
        $this->dataForView['categoriesTree'] = $categoriesTree;

        $data = [];
        foreach ($categoriesTree as $category) {
            $data[] = $category->loadForNav();
        }
        $this->dataForView['categoriesNav'] = $data;
        $this->dataForView['siteConfig'] = Configuration::find(1);

        $this->dataForView['cart'] = $this->_createCart();
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
