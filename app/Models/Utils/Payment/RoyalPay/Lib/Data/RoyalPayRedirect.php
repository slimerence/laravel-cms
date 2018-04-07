<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/11/17
 * Time: 6:15 PM
 */

namespace App\Models\Utils\Payment\RoyalPay\Lib\Data;


/**
 * QRCode支付跳转对象
 * @author Leijid
 */
class RoyalPayRedirect extends RoyalPayDataBase
{
    /**
     * 设置支付成功后跳转页面
     * @param string $value
     **/
    public function setRedirect($value)
    {
        $this->queryValues['redirect'] = $value;
    }

    /**
     * 获取支付成功后跳转页面
     * @return 值
     **/
    public function getRedirect()
    {
        return $this->queryValues['redirect'];
    }

    /**
     * 判断支付成功后跳转页面是否存在
     * @return true 或 false
     **/
    public function isRedirectSet()
    {
        return array_key_exists('redirect', $this->queryValues);
    }
}