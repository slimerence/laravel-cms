<?php

namespace App\Models\Utils\Payment\RoyalPay\Lib\Data;
/**
 * 查询退款状态对象
 * @author Leijid
 */
class RoyalPayQueryOrders extends RoyalPayDataBase
{
    /**
     * 设置订单创建日期，'yyyyMMdd'格式，澳洲东部时间，不填默认查询所有订单
     * @param string $value
     **/
    public function setDate($value)
    {
        $this->queryValues['date'] = $value;
    }

    /**
     * 获取订单创建日期
     * @return 值
     **/
    public function getDate()
    {
        return $this->queryValues['date'];
    }

    /**
     * 判断订单创建日期是否存在
     * @return true 或 false
     **/
    public function isDateSet()
    {
        return array_key_exists('date', $this->queryValues);
    }

    /**
     * 设置订单状态
     * ALL:全部订单，包括未完成订单和已关闭订单
     * PAID:只列出支付过的订单，包括存在退款订单
     * REFUNDED:只列出存在退款订单
     * 默认值: ALL
     * 允许值: 'ALL', 'PAID', 'REFUNDED'
     * @param string $value
     **/
    public function setStatus($value = 'ALL')
    {
        $this->queryValues['status'] = $value;
    }

    /**
     * 获取订单状态
     * @return 值
     **/
    public function getStatus()
    {
        return $this->queryValues['status'];
    }

    /**
     * 判断订单状态是否存在
     * @return true 或 false
     **/
    public function isStatusSet()
    {
        return array_key_exists('status', $this->queryValues);
    }

    /**
     * 设置页码，从1开始计算
     * 默认值: 1
     * @param int $value
     **/
    public function setPage($value = 1)
    {
        $this->queryValues['page'] = $value;
    }

    /**
     * 获取页码
     * @return 值
     **/
    public function getPage()
    {
        return $this->queryValues['page'];
    }

    /**
     * 判断页码是否存在
     * @return true 或 false
     **/
    public function isPageSet()
    {
        return array_key_exists('page', $this->queryValues);
    }

    /**
     * 设置每页条数
     * 默认值: 10
     * @param int $value
     **/
    public function setLimit($value = 10)
    {
        $this->queryValues['limit'] = $value;
    }

    /**
     * 获取每页条数
     * @return 值
     **/
    public function getLimit()
    {
        return $this->queryValues['limit'];
    }

    /**
     * 判断每页条数是否存在
     * @return true 或 false
     **/
    public function isLimitSet()
    {
        return array_key_exists('limit', $this->queryValues);
    }
}