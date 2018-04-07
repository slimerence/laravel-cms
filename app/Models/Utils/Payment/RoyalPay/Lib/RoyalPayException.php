<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/11/17
 * Time: 6:05 PM
 */

namespace App\Models\Utils\Payment\RoyalPay\Lib;

use Exception;
/**
 *
 * RoyalPay支付API异常类
 * @author Leijid
 *
 */
class RoyalPayException extends Exception
{
    public function errorMessage()
    {
        return $this->getMessage();
    }
}
