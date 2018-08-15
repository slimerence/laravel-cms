<?php

namespace App\Http\Controllers\Backend;

use App\Models\Utils\UserGroup;
use App\User;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class Users extends Controller
{
    /**
     * 加载系统用户的列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $customers = User::where('role',UserGroup::$OPERATOR)
            ->orderBy('id','desc')
            ->paginate(config('system.PAGE_SIZE'));
        $this->dataForView['users'] = $customers;
        $this->dataForView['menuName'] = 'users';
        return view('backend.users.index', $this->dataForView);
    }

    /**
     * 添加菜单的表单
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(){
        $this->dataForView['menuName'] = 'users';
        $this->dataForView['user'] = new User();
        $this->dataForView['groups'] = Group::where('id','>',1)->orderBy('name','asc')->get();
        return view('backend.users.add', $this->dataForView);
    }

    /**
     * 加载修改视图
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, Request $request){
        $this->dataForView['menuName'] = 'users';
        $this->dataForView['user'] = User::find($id);
        $this->dataForView['groups'] = Group::where('id','>',1)->orderBy('name','asc')->get();
        return view('backend.users.add', $this->dataForView);
    }

    /**
     * 加载用户更新密码的表单
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update_password(Request $request){
        if($request->has('customer_id')){
            // 表示要更新Customer的密码
            $user = User::GetByUuid($request->get('customer_id'));
        }else{
            $user = Auth::user();
        }

        if($user){
            $this->dataForView['user'] = $user;
            $this->dataForView['menuName'] = 'update-password';
            return view('backend.users.update_password',$this->dataForView);
        }else{
            return view('404',$this->dataForView);
        }
    }

    /**
     * 更新用户的密码
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_password_handler(Request $request){
        $user = User::find($request->get('id'));
        $user->password = Hash::make($request->get('password'));
        $user->save();

        if($user->role == UserGroup::$GENERAL_CUSTOMER || $user->role == UserGroup::$WHOLESALE_CUSTOMER){
            return redirect()->route('customers');
        }else{
            return redirect()->route('system-users');
        }
    }

    /**
     * 保存客户账户信息
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request){
        $data = $request->all();
        unset($data['_token']);

        $customerId = $data['id'];
        if (empty($customerId)){
            $data['password'] = Hash::make($data['password']);
            $data['uuid'] = Uuid::uuid4()->toString();
            User::create($data);
        } else{
            unset($data['id']);
            User::where('id',$customerId)->update($data);
        }
        return redirect()->route('system-users');
    }

    /**
     * 删除用户
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($uuid){
        if(Auth::user()->role == UserGroup::$ADMINISTRATOR){
            User::GetByUuid($uuid)->delete();
        }
        return redirect()->route('system-users');
    }
}
