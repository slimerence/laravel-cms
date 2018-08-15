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
use App\Models\Settings\PaymentMethod;

class PayPalTool
{
    private $libName;
    private $paymentMethod;

    //
    private $username;
    private $password;
    private $signature;
    private $brandName;
    private $isLiveMode;

    public function __construct(PaymentMethod $method)
    {
        $this->libName = str_replace(' ','_',$method->name);
        $this->paymentMethod = $method;

        $this->isLiveMode = $this->paymentMethod->isLiveMode();
        $this->username = $this->paymentMethod->getApiToken();
        $this->signature = $this->paymentMethod->getApiSecret();

        // 为了安全, PayPal的Password放到env中
        $this->password = env('PAYPAL_PASSWORD');
        $this->brandName = env('APP_NAME');
    }

    /**
     * 获取网关对象
     * @return \Omnipay\Common\GatewayInterface
     */
    public function gateway()
    {
        $gateway = Omnipay::create($this->libName);
        $gateway->setUsername($this->username);
        $gateway->setPassword($this->password);
        $gateway->setSignature($this->signature);
        $gateway->setTestMode(!$this->isLiveMode);
        $gateway->setBrandName($this->brandName);
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