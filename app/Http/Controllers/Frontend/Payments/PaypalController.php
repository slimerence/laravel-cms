<?php

namespace App\Http\Controllers\Frontend\Payments;

use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Utils\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class PaypalController extends Controller
{

    // Create a new instance with our paypal credentials
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 用户取消操作的时候
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function cancelled(Request $request){
        $order = Order::GetByUuid($request->get('order_id'));
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
        $order = Order::GetByUuid($request->get('order_id'));
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

    public function paypal_webhook(Request $request){

        Log::info('PayPal_Hook',$request->all());
    }
}
