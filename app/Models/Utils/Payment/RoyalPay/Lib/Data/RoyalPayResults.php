<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/11/17
 * Time: 6:12 PM
 */

namespace App\Models\Utils\Payment\RoyalPay\Lib\Data;

use App\Models\Utils\Payment\RoyalPay\Lib\RoyalPayConfig;
use App\Models\Utils\Payment\RoyalPay\Lib\RoyalPayException;
/**
 *
 * 接口调用结果类
 * @author Leijid
 *
 */
class RoyalPayResults extends RoyalPayDataBase
{

    /**
     *
     * 使用数组初始化
     * @param array $array
     */
    public function fromArray($array)
    {
        $this->bodyValues = json_decode($array, true);
    }

    /**
     * 将json转为array
     * @param string $json
     * @throws RoyalPayException
     *
     * 返回信息:
     * @return_code  return_msg
     * --------------------------------------
     * ORDER_NOT_EXIST      订单不存在
     * ORDER_MISMATCH       订单号与商户不匹配
     * SYSTEMERROR          系统内部异常
     * INVALID_SHORT_ID     商户编码不合法或没有对应商户
     * SIGN_TIMEOUT         签名超时，time字段与服务器时间相差超过5分钟
     * INVALID_SIGN         签名错误
     * PARAM_INVALID        参数不符合要求，具体细节可参考return_msg字段
     * NOT_PERMITTED        未开通网关支付权限
     * --------------------------------------
     */
    public static function init($array)
    {
        $obj = new self();
        $obj->fromArray($array);
        return $obj->getBodyValues();
    }
}
