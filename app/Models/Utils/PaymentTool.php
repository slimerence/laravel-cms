<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 10/10/17
 * Time: 1:41 AM
 */

namespace App\Models\Utils;

/**
 * 支付相关的工具类
 * Class PaymentTool
 * @package App\Models\Utils
 */
class PaymentTool
{
    public static $TYPE_PLACE_ORDER = 1;
    public static $TYPE_CASH        = 2;
    public static $TYPE_CREDIT_CARD = 3;
    public static $TYPE_WECHAT      = 4;
    public static $TYPE_ALIPAY      = 5;
    public static $TYPE_PAYPAL      = 6;
    public static $TYPE_MONEY_ORDER = 7;
    public static $TYPE_CHEQUE      = 8;
    public static $TYPE_APPLE_PAY   = 9;
    public static $TYPE_STRIPE      = 10;
    public static $TYPE_PAYPAL_PRO  = 11;

    /**
     * 代表不同支付方式的字符串
     * @var string
     */
    public static $METHOD_ID_PLACE_ORDER    = 'pm-place-order';
    public static $METHOD_ID_CASH           = 'pm-cash';
    public static $METHOD_ID_CREDIT_CARD    = 'pm-credit-card';
    public static $METHOD_ID_WECHAT         = 'pm-wechat';
    public static $METHOD_ID_ALIPAY         = 'pm-alipay';
    public static $METHOD_ID_PAYPAL_EXPRESS = 'pm-paypal-express';
    public static $METHOD_ID_MONEY_ORDER    = 'pm-money-order';
    public static $METHOD_ID_CHEQUE         = 'pm-cheque';
    public static $METHOD_ID_APPLE_PAY      = 'pm-apple-pay';
    public static $METHOD_ID_STRIPE         = 'pm-stripe';
    public static $METHOD_ID_PAYPAL_PRO     = 'pm-paypal-pro';


    /**
     * 检查给定的方法ID是否被支持
     * @param null $paymentMethod
     * @return bool
     */
    public static function SupportThis($paymentMethod=null){
        if($paymentMethod){
            $methods = self::_Methods();
            return in_array($paymentMethod, $methods);
        }
        return false;
    }

    /**
     * 根据戈丁的id string 返回对应的支付方式的整数值
     * @param string $idString
     * @return int
     */
    public static function GetMethodTypeById($idString)
    {
        $map = self::_MethodsMap();
        return isset($map[$idString]) ? $map[$idString] : null;
    }

    private static function _Methods(){
        return [
            self::$METHOD_ID_PLACE_ORDER,
            self::$METHOD_ID_CASH,
            self::$METHOD_ID_CREDIT_CARD,
            self::$METHOD_ID_WECHAT,
            self::$METHOD_ID_ALIPAY,
            self::$METHOD_ID_PAYPAL_EXPRESS,
            self::$METHOD_ID_MONEY_ORDER,
            self::$METHOD_ID_CHEQUE,
            self::$METHOD_ID_APPLE_PAY,
            self::$METHOD_ID_STRIPE,
            self::$METHOD_ID_PAYPAL_PRO
        ];
    }

    private static function _MethodsMap(){
        return [
            self::$METHOD_ID_PLACE_ORDER    => self::$TYPE_PLACE_ORDER,
            self::$METHOD_ID_CASH           => self::$TYPE_CASH,
            self::$METHOD_ID_CREDIT_CARD    => self::$TYPE_CREDIT_CARD,
            self::$METHOD_ID_WECHAT         => self::$TYPE_WECHAT,
            self::$METHOD_ID_ALIPAY         => self::$TYPE_ALIPAY,
            self::$METHOD_ID_PAYPAL_EXPRESS => self::$TYPE_PAYPAL,
            self::$METHOD_ID_MONEY_ORDER    => self::$TYPE_MONEY_ORDER,
            self::$METHOD_ID_CHEQUE         => self::$TYPE_CHEQUE,
            self::$METHOD_ID_APPLE_PAY      => self::$TYPE_APPLE_PAY,
            self::$METHOD_ID_STRIPE         => self::$TYPE_STRIPE,
            self::$METHOD_ID_PAYPAL_PRO     => self::$TYPE_PAYPAL_PRO
        ];
    }

    /**
     * 获取所有的系统所支持的支付方式
     * @return array
     */
    public static function AllTypes(){
        return [
            self::$TYPE_PLACE_ORDER => 'Place Order',
            self::$TYPE_CASH => 'CASH',
            self::$TYPE_CREDIT_CARD => 'Credit Card',
            self::$TYPE_WECHAT => '微信支付',
            self::$TYPE_ALIPAY => '支付宝',
            self::$TYPE_MONEY_ORDER => 'Money Order',
            self::$TYPE_CHEQUE => 'Cheque',
            self::$TYPE_APPLE_PAY => 'Apple Pay'
        ];
    }

    /**
     * 获取支付方式的名字
     * @param Integer $type
     * @return string
     */
    public static function GetTypeName($type){
        $types = self::AllTypes();
        return isset($types[$type]) ? $types[$type] : 'N.A';
    }
}