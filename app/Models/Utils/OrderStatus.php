<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 9/10/17
 * Time: 7:41 PM
 */

namespace App\Models\Utils;


class OrderStatus
{
    public static $PENDING = 1;
    public static $APPROVED = 2;  // 支付成功, 或者财务主管批准
    public static $DECLINED = 3;  // 支付失败, 或者财务主管拒绝
    public static $INVOICED = 4;
    public static $DELIVERED = 5;
    public static $COMPLETE = 6;

    public static function All(){
        return [
            self::$PENDING=>'Pending',
            self::$APPROVED=>'Processing',
            self::$DECLINED=>'Declined',
            self::$INVOICED=>'Invoiced',
            self::$DELIVERED=>'Delivered',
            self::$COMPLETE=>'Complete'
        ];
    }

    public static function GetCssClass(){
        return [
            self::$PENDING=>'badge badge-secondary',
            self::$APPROVED=>'badge badge-success',
            self::$DECLINED=>'badge badge-danger',
            self::$INVOICED=>'badge badge-info',
            self::$DELIVERED=>'badge badge-warning',
            self::$COMPLETE=>'badge badge-primary'
        ];
    }

    public static function GetName($key, $richText=true){
        $types = self::All();
        $css = self::GetCssClass();
        if($richText){
            return '<span class="'.$css[$key].'">'.$types[$key].'</span>';
        }
        return $types[$key];
    }
}