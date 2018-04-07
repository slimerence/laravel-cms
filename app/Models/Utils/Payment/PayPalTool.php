<?php
namespace App\Models\Utils\Payment;
/**
 * 和 PayPal 相关的工具类
 * Class EmailTool
 * @package App\Models\Utils
 */
use Omnipay\Omnipay;
use Omnipay\Common\Message\ResponseInterface;
use App\Models\Order\Order;

class PayPalTool
{
    private $libName;
    public function __construct($libName)
    {
        $this->libName = $libName;
    }

    public function gateway()
    {
        $gateway = Omnipay::create($this->libName);
        $mode = env('RUNNING_IN_TEST_MODE',true) ? 'sandbox' : 'live';
        $gateway->setUsername(config('paypal.'.$mode.'.api.username'));
        $gateway->setPassword(config('paypal.'.$mode.'.api.password'));
        $gateway->setSignature(config('paypal.'.$mode.'.api.signature'));
        $gateway->setTestMode(env('RUNNING_IN_TEST_MODE',true));
        $gateway->setBrandName(config('app.name'));
        return $gateway;
    }

    /**
     * 执行购买的方法
     * @param Order $order
     * @return ResponseInterface
     */
    public function purchase(Order $order)
    {
        $response = $this->gateway()
            ->purchase([
                'amount' => $this->formatAmount($order),
                'transactionId' => $order->serial_number,
                'currency' => 'AUD',
                'cancelUrl' => $this->getCancelUrl($order),
                'returnUrl' => $this->getReturnUrl($order),
            ])
            ->send();
        return $response;
    }

    /**
     * @param array $parameters
     * @return ResponseInterface
     */
    public function complete(array $parameters)
    {
        $response = $this->gateway()
            ->completePurchase($parameters)
            ->send();

        return $response;
    }

    /**
     * 格式化订单总金额的方法
     * @param Order $order
     * @return string
     */
    public function formatAmount(Order $order)
    {
        return number_format($order->getTotalFinal(), 2, '.', '');
    }

    /**
     * 取消订单的回调路径
     * @param Order $order
     * @return string
     */
    public function getCancelUrl(Order $order)
    {
        return route('paypal.checkout.cancelled', ['order_id'=>$order->uuid]);
    }

    /**
     * 完成订单的回调路径
     * @param Order $order
     * @return string
     */
    public function getReturnUrl(Order $order)
    {
        return route('paypal.checkout.completed', ['order_id'=>$order->uuid]);
    }

    /**
     * PayPal webhook 的回调路径
     * @param Order $order
     * @return string
     */
    public function getNotifyUrl(Order $order)
    {
        $env = env('RUNNING_IN_TEST_MODE', true) ? "sandbox" : 'live';

        return route('webhook.paypal.ipn', [['order_id'=>$order->uuid], 'env'=>$env]);
    }
}