<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Utils\JsonBuilder;
use App\Models\Utils\UserGroup;
use App\Models\Group;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Events\Order\Approved;
use App\Events\Order\Declined;

class Orders extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * 展示我的订单, 加上第二个参数表示同时清空购物车的内容
     * @param null $userUuid
     * @param null $clearCart
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function my_orders($userUuid=null,$clearCart=null)
    {
        $this->dataForView['menuName'] = 'my_orders';
        $this->dataForView['orders'] = Order::where('user_id',session('user_data.id'))
            ->orderBy('id','desc')->paginate(config('system.PAGE_SIZE'));

        if($clearCart == 'yes'){
            // 同时清空购物车的内容
            $this->getCart()->destroy();
        }

        $this->dataForView['vuejs_libs_required'] = ['my_orders'];

        return view(
            'frontend.default.order.my_orders',
            $this->dataForView
        );
    }

    /**
     * 显示订单的详情
     * @param $userUuid
     * @param $orderUuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_order($userUuid, $orderUuid){
        $this->dataForView['menuName'] = 'my_orders';
        $order = Order::GetByUuid($orderUuid);
        $this->dataForView['order'] = $order;

        $this->dataForView['vuejs_libs_required'] = ['view_order'];

        return view(
            'frontend.default.order.view_order',
            $this->dataForView
        );
    }

    /**
     * FC 同意订单的方法
     * @param $userUuid
     * @param $orderUuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function approve($userUuid, $orderUuid){
        if(session('user_data')){
            if(session('user_data.role') == UserGroup::$FINANCE_CONTROLLER){
                // 表明访问的是FC
                $order = Order::GetByUuid($orderUuid);
                if($order){
                    // 验证一下这个order是否归这个FC 管理
                    $uh = UserGroup::where('user_id',session('user_data.id'))
                        ->where('group_id',$order->group_id)
                        ->first();
                    if($uh){
                        // 验证成功
                        if($order->approve()){
                            session()->flash(
                                'msg',
                                ['content' => 'Order #: ' . $order->serial_number . ' has been approved!', 'status' => 'success']);
                            // 抛出订单授权成功事件
                            event(new Approved($order, User::find(session('user_data.id'))));
                        }else{
                            session()->flash(
                                'msg',
                                ['content' => 'System is busy, please try to approve the order later!', 'status' => 'danger']);
                        }
                    }else{
                        session()->flash(
                            'msg',
                            ['content' => 'You can approve an order which doesn\'t belong to you!', 'status' => 'danger']);
                    }
                }else{
                    session()->flash(
                        'msg',
                        ['content' => 'Can\'t locate the order your requested, please try later!', 'status' => 'danger']);
                }
                // 跳转回订单列表页
                return redirect('frontend/my_orders/'.session('user_data.uuid'));
            }else{
                return redirect('frontend/home');
            }
        }else{
            return redirect('login');
        }
    }

    /**
     * FC 拒绝订单的方法
     * 如果是Post的方式, 则为Ajax的请求
     * @param $userUuid
     * @param $orderUuid
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function decline($userUuid, $orderUuid, Request $request){
        if(session('user_data')){
            if(session('user_data.role') == UserGroup::$FINANCE_CONTROLLER){
                // 表明访问的是FC
                $order = Order::GetByUuid($orderUuid);
                if($order){
                    // 验证一下这个order是否归这个FC 管理
                    $uh = UserGroup::where('user_id',session('user_data.id'))
                        ->where('group_id',$order->group_id)
                        ->first();
                    if($uh){
                        // 验证成功
                        if($order->decline($request->get('note'))){
                            session()->flash(
                                'msg',
                                ['content' => 'Order #: ' . $order->serial_number . ' has been declined!', 'status' => 'success']);
                            // 抛出订单拒绝成功事件
                            event(
                                new Declined(
                                    $order,
                                    User::find(session('user_data.id')),
                                    $request->get('note')
                                )
                            );

                            if($request->isMethod('post')){
                                return JsonBuilder::Success();
                            }
                        }else{
                            $content = 'System is busy, please try to declined the order later!';
                            session()->flash(
                                'msg',
                                ['content' => $content, 'status' => 'danger']);
                            if($request->isMethod('post')){
                                return JsonBuilder::Error($content);
                            }
                        }
                    }else{
                        $content = 'You can declined an order which doesn\'t belong to you!';
                        session()->flash(
                            'msg',
                            ['content' => $content, 'status' => 'danger']);
                        if($request->isMethod('post')){
                            return JsonBuilder::Error($content);
                        }
                    }
                }else{
                    $content = 'Can\'t locate the order your requested, please try later!';
                    session()->flash(
                        'msg',
                        ['content' => $content, 'status' => 'danger']);
                    if($request->isMethod('post')){
                        return JsonBuilder::Error($content);
                    }
                }

                if($request->isMethod('get')){
                    // 跳转回订单列表页
                    return redirect('frontend/my_orders/'.session('user_data.uuid'));
                }
            }else{
                return redirect('frontend/home');
            }
        }else{
            return redirect('login');
        }
    }
}
