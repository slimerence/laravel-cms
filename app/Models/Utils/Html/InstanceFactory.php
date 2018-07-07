<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/7/18
 * Time: 9:57 PM
 */

namespace App\Models\Utils\Html;
use App\Models\Utils\Html\Interfaces\IInput;

class InstanceFactory
{
    const INPUT_IMPL_CLASS_PREFIX = '\App\Models\Utils\Html\Form';
    const INPUT_IMPL_CLASS_NAME = 'InputImpl';
    /**
     * @var InstanceFactory
     */
    private static $worker;

    private function __construct()
    {
    }

    /**
     * 单例工程类
     * @return InstanceFactory
     */
    public static function GetWorker(){
        if(!self::$worker){
            self::$worker = new InstanceFactory();
        }
        return self::$worker;
    }

    /**
     * @param $themeName
     * @return IInput
     */
    public function buildInputHelper($themeName){
        $classNameFullName = self::INPUT_IMPL_CLASS_PREFIX.'\\'.$themeName.'\\'.self::INPUT_IMPL_CLASS_NAME;
        return new $classNameFullName();
    }
}