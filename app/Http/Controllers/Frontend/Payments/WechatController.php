<?php

namespace App\Http\Controllers\Frontend\Payments;

use App\Models\Utils\Payment\RoyalPayTool;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Utils\OrderStatus;

class WechatController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 订单取消之后的处理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function cancelled(Request $request){
        $orderId = $request->get('order_id');
        $order = Order::GetByUuid(RoyalPayTool::decodeOrderSerialNumber($orderId));
        if($order){
            // 把所有的订单项和订单都删除
            OrderItem::where('order_id',$order->id)->delete();
            $order->delete();
            session()->flash('msg', ['content'=>'Order is cancelled!','status'=>'danger']);
            // 删除之后, 跳转回购物车页
            return redirect('/frontend/place_order_checkout');
        }
        return '404';
    }

    /**
     * 订单完成之后的处理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function completed(Request $request){
        $order = Order::GetByUuid(RoyalPayTool::decodeOrderSerialNumber($request->get('order_id')));
        if($order){
            // 完成之后, 情况购物车， 完成订单状态的切换
            $order->status = OrderStatus::$APPROVED;
            $this->getCart()->destroy();
            if($order->save()){
                session()->flash('msg', ['content'=>'Order #'.$order->serial_number.' is in progress!','status'=>'success']);
            }else{
                session()->flash('msg', ['content'=>'System is busy, your order #'.$order->serial_number.' is not confirmed completely! Please call us','status'=>'danger']);
            }

            // 完成之后, 跳转到用户主页
            return redirect('/frontend/my_orders/'.session('user_data.uuid'));
        }
        return '404';
    }

    public function notify(Request $request){

    }
}
