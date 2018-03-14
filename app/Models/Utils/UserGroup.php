<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 9/10/17
 * Time: 7:33 PM
 */

namespace App\Models\Utils;

use App\User;

class UserGroup
{
    public static $ADMINISTRATOR        = 1;
    public static $OPERATOR             = 2;
    public static $FINANCE_CONTROLLER   = 3;
    public static $PURCHASING_MANAGER   = 4;
    public static $GENERAL_CUSTOMER     = 5;
    public static $WHOLESALE_CUSTOMER   = 6;

    public static $ADMIN_LOGIN_REDIRECT = '/backend/configurations';
    public static $CUSTOMER_LOGIN_REDIRECT = '/frontend/home';
    public static $FINANCE_CONTROLLER_LOGIN_REDIRECT = '/frontend/my_orders';

    public static $ACCOUNT_EMAIL_CONFIRMED = 2;
    public static $ACCOUNT_EMAIL_PENDING = 1;

    public static function Customers(){
        return [
            self::$PURCHASING_MANAGER   => 'Purchasing Manager',
            self::$FINANCE_CONTROLLER   => 'Finance Controller',
            self::$GENERAL_CUSTOMER     => 'General Customer',
            self::$WHOLESALE_CUSTOMER   => 'Wholesale Customer',
        ];
    }

    /**
     * 获取角色的名称
     * @param $role
     * @return mixed|null
     */
    public static function RoleName($role){
        $roles = self::Customers();
        return isset($roles[$role]) ? $roles[$role] : null;
    }

    /**
     * 检查给定的用户是否可以去后台
     * @param User $user
     * @return bool
     */
    public static function CanGoBackendAfterLogin(User $user){
        return $user->role == self::$ADMINISTRATOR || $user->role == self::$OPERATOR;
    }

    /**
     * 根据给定的用户来判断其角色
     * @param User $user
     * @return bool
     */
    public static function CanGoFinanceControllerDashboard(User $user){
        return $user->role == self::$FINANCE_CONTROLLER;
    }
}