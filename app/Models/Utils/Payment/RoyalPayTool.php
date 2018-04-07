<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/11/17
 * Time: 6:31 PM
 */

namespace App\Models\Utils\Payment;

use App\Models\Order\Order;
use App\Models\Utils\Payment\RoyalPay\Lib\RoyalPayApi;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayUnifiedOrder;
use App\Models\Utils\Payment\RoyalPay\Lib\RoyalPayConfig;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayRedirect;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayExchangeRate;
use Illuminate\Http\Request;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayDataBase;

class RoyalPayTool 
{
    private $input = null;
    private $order = null;
    private $currency = null;
    private $qrCode = null;  //
    private $redirect = null;

    public function __construct($currency='AUD')
    {
        $this->input = new RoyalPayUnifiedOrder();
        $this->currency = $currency;
    }

    /**
     * 购买订单的方法
     * @param Order $order
     * @return $this
     */
    public function purchase(Order $order){
        if($this->_init($order)){
            $this->qrCode = RoyalPayApi::qrOrder($this->input);
            //跳转
            $this->redirect = new RoyalPayRedirect();
            $returnUrl = $this->getReturnUrl().'?order_id='.strval($this->input->getOrderId());
            $this->redirect->setRedirect(urlencode($returnUrl));
        }
        return $this;
    }

    public function send(){
        
    }

    /**
     * 获取得到的支付二维码图片地址
     * @return mixed
     */
    public function getQrCodeUrl(){
        return $this->qrCode ? $this->qrCode["code_url"] : null;
    }

    /**
     * 获取得到的支付二维码数据中的支付跳转地址
     * @return mixed
     */
    public function getQrCodePayUrl(){
        return $this->qrCode ? $this->qrCode["pay_url"] : null;
    }

    /**
     * 获取跳转到RoyalPay进行支付的url网址
     * @return null|string
     */
    public function getQrRedirectUrl(){
        return $this->qrCode ? RoyalPayApi::getQRRedirectUrl($this->getQrCodePayUrl(),$this->redirect) : null;
    }

    /**
     * 处理成功回调的方法
     * @param Request $request
     * @param Boolean $async
     * @return Order/null
     */
    public function complete(Request $request, $async = false)
    {
        $input = new RoyalPayDataBase();
        $input->setNonceStr($request->get('nonce_str'));
        $input->setTime($request->get('time'));
        $input->setSign();

        // 验证返回消息
        if($input->getSign() === $request->get('sign')){
            if($async){
                // 异步通知, 相当于对 Notify url的响应
            }else{
                // 相当于对 Return url的响应
                $serialNumber = str_replace(RoyalPayConfig::PARTNER_CODE,'',$request->get('order_id'));
                // 更新订单的状态
                return Order::OrderPaymentConfirmedBy($request->get('order_id'), $serialNumber);
            }
        }
        return false;
    }

    private function _init(Order $order){
        $this->order = $order;
        $this->input->setOrderId($this->getPartnerOrderId($order));
        $this->input->setDescription($this->order->getOrderDescription());
        $this->input->setPrice($this->order->getTotalFinal() * 100);  // 这里的货币单位是分
        $this->input->setCurrency($this->currency);
//        $this->input->setCurrency("AUD");
        $this->input->setNotifyUrl($this->getNotifyUrl());
        $this->input->setOperator(config('app.name'));
        $currency = $this->input->getCurrency();

        if (!empty($currency) && $currency == 'CNY') {
            //建议缓存汇率,每天更新一次,遇节假日或其他无汇率更新情况,可取最近一个工作日的汇率
            $inputRate = new RoyalPayExchangeRate();
            $rate = RoyalPayApi::exchangeRate($inputRate);

            if ($rate['return_code'] == 'SUCCESS') {
                $real_pay_amt = $this->input->getPrice() / $rate['rate'] / 100;
                if ($real_pay_amt < 0.01) {
                    echo '人民币转换澳元后必须大于0.01澳元';
                    exit();
                }
            }
        }
        return true;
    }

    /**
     * 产生订单的 Partner Order ID
     * @param Order $order
     * @return string
     */
    public function getPartnerOrderId(Order $order){
        return RoyalPayConfig::PARTNER_CODE.$order->serial_number;
    }

    /**
     * 消息通知回调
     * @return string
     */
    public function getNotifyUrl(){
        return url('api/payment/weixin/notify');
//        return "http://115.29.162.214/example/notify.php";
    }

    /**
     * 成功时的回调
     * @return string
     */
    public function getReturnUrl(){
        return url('api/payment/weixin/success');
//        return 'http://115.29.162.214/example/success.php';
    }

    /**
     * 取消时的回调
     */
    public function getCancelUrl(){

    }
}