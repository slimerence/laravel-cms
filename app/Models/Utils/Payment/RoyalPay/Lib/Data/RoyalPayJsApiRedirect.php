<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/11/17
 * Time: 6:16 PM
 */

namespace App\Models\Utils\Payment\RoyalPay\Lib\Data;


/**
 * jsapi支付跳转对象
 * @author Leijid
 */
class RoyalPayJsApiRedirect extends RoyalPayRedirect
{
    /**
     * 设置是否直接支付
     * @param string $value
     **/
    public function setDirectPay($value)
    {
        $this->queryValues['directpay'] = $value;
    }

    /**
     * 获取是否直接支付
     * @return 值
     **/
    public function getDirectPay()
    {
        return $this->queryValues['directpay'];
    }

    /**
     * 判断直接支付是否存在
     * @return true 或 false
     **/
    public function isDirectPaySet()
    {
        return array_key_exists('directpay', $this->queryValues);
    }
}
