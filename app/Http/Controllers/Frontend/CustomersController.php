<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Utils\UserGroup as UserGroupTool;
use App\Events\UserCreated;
use Ramsey\Uuid\Uuid;
use Hash;
use App\Models\Newsletter\UserSubscribe;
use Illuminate\Support\Facades\Validator;

class CustomersController extends Controller
{
    /**
     * 加载普通客户登录表单的方法
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(Request $request){
        $this->dataForView['menuName'] = 'home';
        $this->dataForView['the_referer'] = $request->headers->get('referer');

        return view(
            'frontend.default.customers.login',
            $this->dataForView
        );
    }

    public function login_check(Request $request){
        dd($request->all());
    }

    /**
     * 加载普通客户注册表单的方法
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register(Request $request){
        $this->dataForView['menuName'] = 'home';
        $this->dataForView['the_referer'] = $request->headers->get('referer');
        return view(
            'frontend.default.customers.register',
            $this->dataForView
        );
    }

    /**
     * 检查给定的电子邮件是否存在的方法
     * @param Request $request
     * @return string
     */
    public function is_email_exist(Request $request){
        $user = User::where('email',$request->get('email'))->orderBy('id','desc')->first();
        if($user){
            // 表示这个账户存在
            return JsonBuilder::Success('no');
        }else{
            return JsonBuilder::Success('ok');
        }
    }

    /**
     * 保存客户数据的方法
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request){
        $data = $request->all();

        $subscribe_me = false;
        if(isset($data['subscribe_me'])){
            $subscribe_me = true;
        }
        unset($data['_token']);
        unset($data['subscribe_me']);

        // 获取Referer
        $referer = $data['the_referer'];

        if($request->has('id')){
            // 更新操作
            $id = $data['id'];
            unset($data['id']);
            $user = User::find($id);
            foreach ($data as $field_name => $field_value) {
                $user->$field_name = $field_value;
            }
            if($user->save()){
                session()->flash('msg', ['content'=>'Account: "'.$data['name'].'" has been updated successfully!','status'=>'success']);
            }else{
                session()->flash('msg', ['content'=>'Account: "'.$data['name'].'" can not be updated, please try again!','status'=>'danger']);
            }
        }else{
            // 这个是注册用户的位置
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'address'=>'required|string|max:80',
                'city'=>'required|string|max:40',
                'state'=>'required|string|max:20',
                'postcode'=>'required|string|max:8'
            ]);
            $validator->validate();

            $initPassword = $data['password'];
            $userData = [
                'uuid'=>Uuid::uuid4()->toString(),
                'password'=>Hash::make($data['password']),
            ];
            $data['uuid'] = $userData['uuid'];
            $data['password'] = $userData['password'];
//            $data['group_id'] = UserGroupTool::$GENERAL_CUSTOMER;

            // 添加操作
            if($user = User::create($data)){
                // 发布事件
                event(new UserCreated($user,$initPassword));

                // 记录用户的订阅记录: 如果用户选择了订阅且是有效的邮件地址
                if($subscribe_me && filter_var($user->email, FILTER_VALIDATE_EMAIL)){
                    UserSubscribe::create([
                        'user_id'=>$user->id,
                        'type'=>UserGroupTool::$GENERAL_CUSTOMER,
                        'email'=>$user->email
                    ]);
                }

                session()->flash('msg', ['content'=>'Account: "'.$data['name'].'" has been created successfully!','status'=>'success']);
            }else{
                session()->flash('msg', ['content'=>'Account: "'.$data['name'].'" can not be created, please try again!','status'=>'danger']);
            }
        }

        if(!empty($referer)){
            // 从哪里来到哪里去
            return redirect($referer);
        }else{
            // 重定向到结账页面
            return redirect('frontend/place_order_checkout');
        }
    }

    /**
     * 展示客户自己的profile
     * @param null $uuid
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function my_profile($uuid=null, Request $request){
        if($uuid && $uuid == session('user_data.uuid')){
            // 表示当前的用户是正常的
            $user = User::find(session('user_data.id'));
            $this->dataForView['menuName'] = 'user';
            $this->dataForView['user'] = $user;
            $this->dataForView['vuejs_libs_required'] = ['my_profile'];
            return view(
                'frontend.default.customers.my_profile',
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

    /**
     * 客户顾客的登录密码
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_password(Request $request){
        if(session('user_data.uuid') == $request->get('id')){
            // 表示当前登录用户是准备操作的用户
            $user = User::GetByUuid($request->get('id'));
            if($user){
                $user->password = \Illuminate\Support\Facades\Hash::make($request->get('new_password'));
                if($user->save()){
                    session()->flash('msg', ['content' => 'Your password has been updated successfully!', 'status' => 'success']);
                }
            }else{
                session()->flash('msg', ['content' => 'System is busy, please try later!', 'status' => 'danger']);
            }
            return redirect('frontend/my_profile/'.session('user_data.uuid'));
        }
        return view('404',$this->dataForView);
    }
}
