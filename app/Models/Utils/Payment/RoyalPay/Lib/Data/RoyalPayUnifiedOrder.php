<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/11/17
 * Time: 6:14 PM
 */

namespace App\Models\Utils\Payment\RoyalPay\Lib\Data;


/**
 * 统一下单对象
 * @author Leijid
 */
class RoyalPayUnifiedOrder extends RoyalPayDataBase
{
    /**
     * 设置商户支付订单号，同一商户唯一
     * @param string $value
     **/
    public function setOrderId($value)
    {
        $this->pathValues['order_id'] = $value;
    }

    /**
     * 获取商户支付订单号
     * @return 值
     **/
    public function getOrderId()
    {
        return $this->pathValues['order_id'];
    }

    /**
     * 判断商户支付订单号是否存在
     * @return true 或 false
     **/
    public function isOrderIdSet()
    {
        return array_key_exists('order_id', $this->pathValues);
    }

    /**
     * 设置订单标题
     * @param string $value
     **/
    public function setDescription($value)
    {
        $this->bodyValues['description'] = $value;
    }

    /**
     * 获取订单标题
     * @return 值
     **/
    public function getDescription()
    {
        return $this->bodyValues['description'];
    }

    /**
     * 判断订单标题是否存在
     * @return true 或 false
     **/
    public function isDescriptionSet()
    {
        return array_key_exists('description', $this->bodyValues);
    }

    /**
     * 设置金额，单位为货币最小单位
     * @param string $value
     **/
    public function setPrice($value)
    {
        $this->bodyValues['price'] = $value;
    }

    /**
     * 获取金额，单位为货币最小单位
     * @return 值
     **/
    public function getPrice()
    {
        return $this->bodyValues['price'];
    }

    /**
     * 判断金额是否存在
     * @return true 或 false
     **/
    public function isPriceSet()
    {
        return array_key_exists('price', $this->bodyValues);
    }

    /**
     * 设置币种代码
     * 默认值: AUD
     * 允许值: AUD, CNY
     * @param string $value
     **/
    public function setCurrency($value)
    {
        $this->bodyValues['currency'] = $value;
    }

    /**
     * 获取币种代码
     * 默认值: AUD
     * 允许值: AUD, CNY
     * @return 值
     **/
    public function getCurrency()
    {
        return $this->bodyValues['currency'];
    }

    /**
     * 判断币种代码是否存在
     * @return true 或 false
     **/
    public function isCurrencySet()
    {
        return array_key_exists('currency', $this->bodyValues);
    }

    /**
     * 设置支付通知url,不填则不会推送支付通知
     * @param string $value
     **/
    public function setNotifyUrl($value)
    {
        $this->bodyValues['notify_url'] = $value;
    }

    /**
     * 获取支付通知url
     * @return 值
     **/
    public function getNotifyUrl()
    {
        return $this->bodyValues['notify_url'];
    }

    /**
     * 判断支付通知url是否存在
     * @return true 或 false
     **/
    public function isNotifyUrlSet()
    {
        return array_key_exists('notify_url', $this->bodyValues);
    }

    /**
     * 设置操作人员标识
     * @param string $value
     **/
    public function setOperator($value)
    {
        $this->bodyValues['operator'] = $value;
    }

    /**
     * 获取操作人员标识
     * @return 值
     **/
    public function getOperator()
    {
        return $this->bodyValues['operator'];
    }

    /**
     * 判断操作人员标识是否存在
     * @return true 或 false
     **/
    public function isOperatorSet()
    {
        return array_key_exists('operator', $this->bodyValues);
    }

}