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
        $this->dataForView['siteConfig'] = Configuration::find(1);
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
}
