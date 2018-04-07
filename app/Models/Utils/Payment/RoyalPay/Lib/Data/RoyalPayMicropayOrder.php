<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/11/17
 * Time: 6:16 PM
 */

namespace App\Models\Utils\Payment\RoyalPay\Lib\Data;


/**
 * 线下支付订单
 * @author Leijid
 */
class RoyalPayMicropayOrder extends RoyalPayUnifiedOrder
{
    /**
     * 设置设备ID
     * @param string $value
     **/
    public function setDeviceId($value)
    {
        $this->bodyValues['device_id'] = $value;
    }

    /**
     * 获取设备ID
     * @return 值
     **/
    public function getDeviceId()
    {
        return $this->bodyValues['device_id'];
    }

    /**
     * 判断设备ID是否存在
     * @return true 或 false
     **/
    public function isDeviceIdSet()
    {
        return array_key_exists('device_id', $this->bodyValues);
    }

    /**
     * 设置扫描用户微信客户端得到的支付码
     * @param string $value
     **/
    public function setAuthCode($value)
    {
        $this->bodyValues['auth_code'] = $value;
    }

    /**
     * 获取扫描用户微信客户端得到的支付码
     * @return 值
     **/
    public function getAuthCode()
    {
        return $this->bodyValues['auth_code'];
    }

    /**
     * 判断扫描用户微信客户端得到的支付码是否存在
     * @return true 或 false
     **/
    public function isAuthCodeSet()
    {
        return array_key_exists('auth_code', $this->bodyValues);
    }
}