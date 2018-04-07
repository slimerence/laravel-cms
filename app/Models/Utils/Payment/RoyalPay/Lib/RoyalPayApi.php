<?php
namespace App\Models\Utils\Payment\RoyalPay\Lib;
/**
 * 流程：
 * 1、创建QRCode支付单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，RoyalPay服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */

use App\Models\Utils\Payment\RoyalPay\Lib\RoyalPayException;
use App\Models\Utils\Payment\RoyalPay\Lib\RoyalPayConfig;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayResults;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayExchangeRate;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayApplyRefund;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayUnifiedOrder;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayRedirect;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayJsApiRedirect;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayMicropayOrder;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayRetailQRCode;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayOrderQuery;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayQueryRefund;
use App\Models\Utils\Payment\RoyalPay\Lib\Data\RoyalPayQueryOrders;

class RoyalPayApi
{
    /**
     *
     * 汇率查询，nonce_str、time不需要填入
     * @param RoyalPayExchangeRate $inputObj
     * @param int $timeOut
     * @throws RoyalPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function exchangeRate($inputObj, $timeOut = 10)
    {
        $partnerCode = RoyalPayConfig::PARTNER_CODE;
        $url = "https://mpay.royalpay.com.au/api/v1.0/gateway/partners/$partnerCode/exchange_rate";
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = RoyalPayResults::init($response);
        return $result;
    }

    /**
     *
     * QR下单，nonce_str、time不需要填入
     * @param RoyalPayUnifiedOrder $inputObj
     * @param int $timeOut
     * @throws RoyalPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function qrOrder($inputObj, $timeOut = 15)
    {
        $partnerCode = RoyalPayConfig::PARTNER_CODE;
        $orderId = $inputObj->getOrderId();
        $url = "https://mpay.royalpay.com.au/api/v1.0/gateway/partners/$partnerCode/orders/$orderId";
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = RoyalPayResults::init($response);
        return $result;
    }

    /**
     *
     * JsApi下单，nonce_str、time不需要填入
     * @param RoyalPayUnifiedOrder $inputObj
     * @param int $timeOut
     * @throws RoyalPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function jsApiOrder($inputObj, $timeOut = 10)
    {
        $partnerCode = RoyalPayConfig::PARTNER_CODE;
        $orderId = $inputObj->getOrderId();
        $url = "https://mpay.royalpay.com.au/api/v1.0/wechat_jsapi_gateway/partners/$partnerCode/orders/$orderId";
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = RoyalPayResults::init($response);
        return $result;
    }

    /**
     *
     * QR支付跳转，nonce_str、time不需要填入
     * @param string $pay_url
     * @param RoyalPayRedirect $inputObj
     * @throws RoyalPayException
     * @return $pay_url 成功时返回，其他抛异常
     */
    public static function getQRRedirectUrl($pay_url, $inputObj)
    {
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $pay_url .= '?' . $inputObj->toQueryParams();
        return $pay_url;
    }

    /**
     *
     * JsApi支付跳转，nonce_str、time不需要填入
     * @param string $pay_url
     * @param RoyalPayJsApiRedirect $inputObj
     * @throws RoyalPayException
     * @return $pay_url 成功时返回，其他抛异常
     */
    public static function getJsApiRedirectUrl($pay_url, $inputObj)
    {
        $directPay = $inputObj->getDirectPay();
        if (empty($directPay)) {
            $inputObj->setDirectPay('false');
        }
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $pay_url .= '?' . $inputObj->toQueryParams();
        return $pay_url;
    }

    /**
     *
     * 线下支付订单，nonce_str、time不需要填入
     * @param RoyalPayMicropayOrder $inputObj
     * @param int $timeOut
     * @throws RoyalPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function micropayOrder($inputObj, $timeOut = 10)
    {
        $partnerCode = RoyalPayConfig::PARTNER_CODE;
        $orderId = $inputObj->getOrderId();
        $url = "https://mpay.royalpay.com.au/api/v1.0/micropay/partners/$partnerCode/orders/$orderId";
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = RoyalPayResults::init($response);
        return $result;
    }

    /**
     *
     * 线下QRCode支付单，nonce_str、time不需要填入
     * @param RoyalPayRetailQRCode $inputObj
     * @param int $timeOut
     * @throws RoyalPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function retailQRCodeOrder($inputObj, $timeOut = 10)
    {
        $partnerCode = RoyalPayConfig::PARTNER_CODE;
        $orderId = $inputObj->getOrderId();
        $url = "https://mpay.royalpay.com.au/api/v1.0/retail_qrcode/partners/$partnerCode/orders/$orderId";
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = RoyalPayResults::init($response);
        return $result;
    }

    /**
     *
     * 查询订单，nonce_str、time不需要填入
     * @param RoyalPayOrderQuery $inputObj
     * @param int $timeOut
     * @throws RoyalPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function orderQuery($inputObj, $timeOut = 10)
    {
        $partnerCode = RoyalPayConfig::PARTNER_CODE;
        $orderId = $inputObj->getOrderId();
        $url = "https://mpay.royalpay.com.au/api/v1.0/gateway/partners/$partnerCode/orders/$orderId";
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = RoyalPayResults::init($response);
        return $result;
    }

    /**
     *
     * 申请退款，nonce_str、time不需要填入
     * @param RoyalPayApplyRefund $inputObj
     * @param int $timeOut
     * @throws RoyalPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function refund($inputObj, $timeOut = 10)
    {
        $partnerCode = RoyalPayConfig::PARTNER_CODE;
        $orderId = $inputObj->getOrderId();
        $refundId = $inputObj->getRefundId();
        $url = "https://mpay.royalpay.com.au/api/v1.0/gateway/partners/$partnerCode/orders/$orderId/refunds/$refundId";
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = RoyalPayResults::init($response);
        return $result;
    }

    /**
     *
     * 查询退款状态，nonce_str、time不需要填入
     * @param RoyalPayQueryRefund $inputObj
     * @param int $timeOut
     * @throws RoyalPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function refundQuery($inputObj, $timeOut = 10)
    {
        $partnerCode = RoyalPayConfig::PARTNER_CODE;
        $orderId = $inputObj->getOrderId();
        $refundId = $inputObj->getRefundId();
        $url = "https://mpay.royalpay.com.au/api/v1.0/gateway/partners/$partnerCode/orders/$orderId/refunds/$refundId";
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = RoyalPayResults::init($response);
        return $result;
    }

    /**
     *
     * 查看账单，nonce_str、time不需要填入
     * @param RoyalPayQueryOrders $inputObj
     * @param int $timeOut
     * @throws RoyalPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function orders($inputObj, $timeOut = 10)
    {
        $partnerCode = RoyalPayConfig::PARTNER_CODE;
        $url = "https://mpay.royalpay.com.au/api/v1.0/gateway/partners/$partnerCode/orders";
        $inputObj->setTime(self::getMillisecond());//时间戳
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign();
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = RoyalPayResults::init($response);
        return $result;
    }

    /**
     *
     * 产生随机字符串，不长于30位
     * @param int $length
     * @return $str 产生的随机字符串
     */
    public static function getNonceStr($length = 30)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 以get方式提交json到对应的接口url
     *
     * @param string $url
     * @param object $inputObj
     * @param int $second url执行超时时间，默认30s
     * @throws RoyalPayException
     */
    private static function getJsonCurl($url, $inputObj, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //如果有配置代理这里就设置代理
        if (RoyalPayConfig::CURL_PROXY_HOST != "0.0.0.0"
            && RoyalPayConfig::CURL_PROXY_PORT != 0
        ) {
            curl_setopt($ch, CURLOPT_PROXY, RoyalPayConfig::CURL_PROXY_HOST);
            curl_setopt($ch, CURLOPT_PROXYPORT, RoyalPayConfig::CURL_PROXY_PORT);
        }
        $url .= '?' . $inputObj->toQueryParams();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        //GET提交方式
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new RoyalPayException("curl出错，错误码:$error");
        }
    }

    /**
     * 以put方式提交json到对应的接口url
     *
     * @param string $url
     * @param object $inputObj
     * @param int $second url执行超时时间，默认30s
     * @throws RoyalPayException
     */
    private static function putJsonCurl($url, $inputObj, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //如果有配置代理这里就设置代理
        if (RoyalPayConfig::CURL_PROXY_HOST != "0.0.0.0"
            && RoyalPayConfig::CURL_PROXY_PORT != 0
        ) {
            curl_setopt($ch, CURLOPT_PROXY, RoyalPayConfig::CURL_PROXY_HOST);
            curl_setopt($ch, CURLOPT_PROXYPORT, RoyalPayConfig::CURL_PROXY_PORT);
        }
        $url .= '?' . $inputObj->toQueryParams();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        //PUT提交方式
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $inputObj->toBodyParams());
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new RoyalPayException("curl出错，错误码:$error");
        }
    }

    /**
     * 获取毫秒级别的时间戳
     */
    private static function getMillisecond()
    {
        //获取毫秒的时间戳
        $time = explode(" ", microtime());
        $millisecond = "000".($time[0] * 1000);
        $millisecond2 = explode(".", $millisecond);
        $millisecond = substr($millisecond2[0],-3);
        $time = $time[1] . $millisecond;
        return $time;
    }
}
