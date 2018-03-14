<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 10/10/17
 * Time: 8:57 PM
 */

namespace App\Models\Utils;


class OptionTool
{
    public static $TYPE_TEXT            = 1;
    public static $TYPE_DROP_DOWN       = 2;
    public static $TYPE_RADIO_BUTTON    = 3;
    public static $TYPE_MULTI_SELECT    = 4;
    public static $TYPE_RICH_TEXT       = 5;
    public static $TYPE_IMAGES          = 6;
    public static $TYPE_VIDEO           = 7;
    public static $TYPE_ATTACHMENT      = 8;

    public static $LOCATION_MAIN = 1;       // 表示紧挨着 short description 后面
    public static $LOCATION_ADDITIONAL = 2; // 放在产品额外信息的位置

    public static function Countries(){
        return [
            'Australia'=>'Australia',
            'New Zealand'=>'New Zealand'
        ];
    }

    public static function States(){
        return [
            'NSW'=>'NSW',
            'VIC'=>'VIC',
            'QLD'=>'QLD',
            'SA'=>'SA',
            'TAS'=>'TAS',
            'WA'=>'WA',
            'ACT'=>'ACT',
            'JBT'=>'JBT',
            'NT'=>'NT',
        ];
    }

    /**
     * 返回产品属性在产品页中的位置
     * @return array
     */
    public static function AttributeLocations(){
        return [
            self::$LOCATION_MAIN => '紧挨着产品简介后面',
            self::$LOCATION_ADDITIONAL => '放在产品额外信息的位置'
        ];
    }

    /**
     * 返回产品option的类型
     * @return array
     */
    public static function AllTypes(){
        return [
            self::$TYPE_TEXT => 'Text',
            self::$TYPE_DROP_DOWN => 'Drop Down',
            self::$TYPE_RADIO_BUTTON => 'Radio',
            self::$TYPE_MULTI_SELECT => 'Multi Select',
            self::$TYPE_RICH_TEXT => 'Rich Text',
            self::$TYPE_IMAGES => 'Images Gallery',
            self::$TYPE_VIDEO => 'Youtube Video',
            self::$TYPE_ATTACHMENT => 'Downloadable Files'
        ];
    }

    /**
     * 返回所有的产品属性的可选类型
     * @return array
     */
    public static function ProductAttributeTypes(){
        return [
            self::$TYPE_TEXT => 'Text',
            self::$TYPE_RICH_TEXT => 'Rich Text',
            self::$TYPE_IMAGES => 'Images Gallery',
            self::$TYPE_VIDEO => 'Youtube Video',
            self::$TYPE_ATTACHMENT => 'Downloadable Files',
        ];
    }

    /**
     * 根据给定的key返回产品Option的名称
     * @param $key
     * @return mixed
     */
    public static function TypeName($key){
        $types = self::AllTypes();
        return $types[$key];
    }

    /**
     * 根据给定的key返回产品属性的名称
     * @param $key
     * @return mixed
     */
    public static function AttributeName($key){
        $types = self::ProductAttributeTypes();
        return $types[$key];
    }
}