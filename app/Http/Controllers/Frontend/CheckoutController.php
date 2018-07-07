<?php
/**
 * 网站前端用的结算控制器类
 */
namespace App\Http\Controllers\Frontend;

use App\Events\Order\Created as OrderCreated;
use App\Jobs\Payment\StripePayment;
use App\Models\Settings\PaymentMethod;
use App\Models\Utils\Payment\RoyalPayTool;
use App\Models\Utils\PaymentTool;
use App\User;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Group;
//use Omnipay;
use App\Events\OrderPlaced;

class CheckoutController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 完成Place Order方式订单的方法
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function place_order_checkout(Request $request){
//        dd($request->all());

        // 检查用户是否登录了, 如果没有登录,那么去登录页
        if(!session()->has('user_data.id')){
//            return redirect('/frontend/customers/login');
            $customer = null;
        }else{
            $customer = User::find(session('user_data.id'));
        }

        $cart = $this->getCart();
        $this->dataForView['cart'] = $cart;

        // 首先确认是post方式并且检查用户选择的支付方式
        if($request->isMethod('post')){
            // 先尝试获取Customer的数据
            if(is_null($customer)){
                $customer = User::GetByUuid($request->get('customerUuid'));
                $this->_saveUserInSession($customer);
            }

            $paymentMethodFound = $request->has('payment_method') && PaymentTool::SupportThis($request->get('payment_method'));
            if(!$paymentMethodFound){
                // 客户没有选择支付方式, 那么就继续留在当前页面
                session()->flash(
                    'msg',
                    ['content' => 'Please select a valid payment method!', 'status' => 'danger']);
            }else{
                $order = null;
                if(is_null($request->get('order_number')) && $request->get('payment_method')==PaymentTool::$METHOD_ID_PLACE_ORDER){
                    // Place Order 必须得提供 Order Number, 但是却没有提供
                    session()->flash(
                        'msg',
                        ['content' => 'Please provide an order number to place!', 'status' => 'danger']);
                }else{
                    $order = Order::PlaceOrder(
                        $customer,
                        $cart,
                        $request->get('order_number'),
                        $request->get('notes'),
                        PaymentTool::GetMethodTypeById($request->get('payment_method'))
                    );

                    if($order){
                        /**
                         * 订单生成成功, 发布订单创建事件
                         * 如果是Place Order, 那么不需要进行支付处理
                         */
                        if($request->get('payment_method') == PaymentTool::$METHOD_ID_PLACE_ORDER){
                            event(new OrderCreated($order, $customer,$request));
                            session()->flash('msg', ['content'=>'Order #'.$order->serial_number.' has been placed!','status'=>'success']);
                            $cart->destroy();
                            return redirect('/frontend/my_orders/'.session('user_data.uuid'));
                        }elseif($request->get('payment_method') == PaymentTool::$METHOD_ID_WECHAT){
                            // 微信支付
                            $royalPayTool = new RoyalPayTool();
                            return redirect($royalPayTool->purchase($order)->getQrRedirectUrl());
                        }elseif($request->get('payment_method') == PaymentTool::$METHOD_ID_STRIPE){
                            // Stripe 信用卡支付
                            $job = new StripePayment($order, $request, $customer);
                            if($job->handle()){
                                // 一切顺利
                                $cart->destroy();
                                session()->flash('msg', ['content'=>'Order #'.$order->serial_number.' is in progress!','status'=>'success']);
                                return redirect('/frontend/my_orders/'.session('user_data.uuid'));
                            }else{
                                session()->flash('msg', ['content'=>'System is Busy, Please try again!','status'=>'danger']);
                            }
                        }else{
                            // 不是 place order 订单, 那么进行支付处理
                            event(new OrderPlaced($cart,$customer,$request,$order));
                        }
                    }else{
                        session()->flash('msg', ['content'=>'System is Busy, Please try again!','status'=>'danger']);
                    }
                }
            }
        }

        // 获取当前用户
        $this->dataForView['user'] = $customer;

        // 获取运费
        $this->dataForView['delivery_charge'] = Group::CalculateDeliveryCharge(
            $customer,$cart->total(),$this->getTotalWeightInCart()
        );

        // 获取系统支持的支付方式
        $this->dataForView['paymentMethods'] = PaymentMethod::GetAllAvailable();

        $this->dataForView['vuejs_libs_required'] = [
            'payment_accordion',
            'guest_checkout'
        ];

        return view(
            _get_frontend_theme_path('checkout.place_order_checkout'),
            $this->dataForView
        );
    }
}
