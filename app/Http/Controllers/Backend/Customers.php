<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Utils\UserGroup;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class Customers extends Controller
{
    /**
     * 获取用户列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $customers = User::whereIn('role',[UserGroup::$GENERAL_CUSTOMER,UserGroup::$WHOLESALE_CUSTOMER])
            ->orderBy('id','desc')
            ->paginate(config('system.PAGE_SIZE'));
        $this->dataForView['customers'] = $customers;
        $this->dataForView['menuName'] = 'customers';
        return view('backend.users.customers', $this->dataForView);
    }

    /**
     * 添加菜单的表单
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(){
        $this->dataForView['menuName'] = 'customers';
        $this->dataForView['groups'] = Group::all();
        $this->dataForView['customer'] = new User();
        return view('backend.users.add_customer', $this->dataForView);
    }

    /**
     * 加载修改视图
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, Request $request){
        $this->dataForView['menuName'] = 'customers';
        $this->dataForView['groups'] = Group::all();
        $this->dataForView['customer'] = User::find($id);
        return view('backend.users.add_customer', $this->dataForView);
    }

    public function delete($uuid){
        if(Auth::user()->role == UserGroup::$ADMINISTRATOR){
            User::GetByUuid($uuid)->delete();
        }
        return redirect()->route('customers');
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
        return redirect()->route('customers');
    }
}
