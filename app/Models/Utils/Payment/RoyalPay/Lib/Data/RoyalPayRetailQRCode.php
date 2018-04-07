<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/11/17
 * Time: 6:17 PM
 */

namespace App\Models\Utils\Payment\RoyalPay\Lib\Data;

/**
 * 线下QRCode支付单
 */
class RoyalPayRetailQRCode extends RoyalPayUnifiedOrder
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
}