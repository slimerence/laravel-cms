<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/11/17
 * Time: 6:18 PM
 */

namespace App\Models\Utils\Payment\RoyalPay\Lib\Data;


/**
 * 查询订单状态对象
 * @author Leijid
 */
class RoyalPayOrderQuery extends RoyalPayDataBase
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
}