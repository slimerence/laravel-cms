<?php

namespace App\Http\Controllers\Backend;

use App\Models\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Utils\UserGroup;
use Illuminate\Support\Facades\Auth;

class Orders extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * 后台的订单管理
     * @return array
     */
    public function my_orders($userUuid=null)
    {
        if(session('user_data.role') == UserGroup::$ADMINISTRATOR){
            // 正确的用户
            $this->dataForView['menuName'] = 'orders';
            $this->dataForView['orders'] = Order::orderBy('id','desc')->paginate(config('system.PAGE_SIZE'));

            $this->dataForView['vuejs_libs_required'] = ['my_orders'];
            return view(
                'backend.order.my_orders',
                $this->dataForView
            );
        }
    }

    /**
     * 后台查看订单详情
     * @param $orderId
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($orderId, Request $request){
        $this->dataForView['menuName'] = 'dashboard';
        $this->dataForView['order'] = Order::find($orderId);

        $this->dataForView['vuejs_libs_required'] = ['view_order'];
        return view(
            'backend.order.view',
            $this->dataForView
        );
    }

    /**
     * 订单的ajax搜索
     * @param Request $request
     * @return string
     */
    public function ajax_search(Request $request){
        if($request->isMethod('post')){
            $keyword = $request->get('key');
            $orders = Order::select('id','place_order_number','status','total','delivery_charge')->where('place_order_number','like',$keyword.'%')
                ->orderBy('id','desc')
                ->take(config('system.PAGE_SIZE'))
                ->get();
            $result = [];
            foreach ($orders as $order) {
                $valueStr = $order->place_order_number.' - '.$order->group_name.', '.config('system.CURRENCY').$order->group;
                $result[] = ['value'=>$valueStr,'id'=>$order->id];
            }
            return JsonBuilder::Success($result);
        }
    }

    /**
     * 将订单切换为订单已发布状态
     * @param $id
     * @param Request $request
     * @return string
     */
    public function ajax_issue_invoice($id, Request $request){
        if(session('user_data') && session('user_data.role') == UserGroup::$ADMINISTRATOR){
            if(Order::IssueInvoice($id)){
                return JsonBuilder::Success();
            }else{
                return JsonBuilder::Error();
            }
        }
    }
}
