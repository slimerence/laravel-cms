<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 9/10/17
 * Time: 7:39 PM
 */

namespace App\Models\Utils;


class ProductType
{
    public static $GENERAL_ITEM     = 1;
    public static $GROUP_SPECIFIED  = 2;

    /**
     * 产品默认的属性集ID
     * @var int
     */
    public static $BASIC_PRODUCT_ATTRIBUTE_SET = 1;

    /**
     * 在添加购物项目进入Cart的时候, 如果产品是有Options的
     * 那么需要添加一个结尾的字串, 以免变成产品购物数量的累加
     * @var string
     */
    public static $PRODUCT_UUID_AFFIX = '____';

    public static function All(){
        return [
            self::$GENERAL_ITEM     =>'General Item',
            self::$GROUP_SPECIFIED  => 'Group Specified'
        ];
    }

    /**
     * 产生一个product 的UUID的tail
     * @return string
     */
    public static function GetProductUuidTail(){
        return ''.time();
    }

    /**
     * 去掉产品uuid的tail以返回真正的uuid的方法
     * @param $uuidWithTail
     * @return null|string
     */
    public static function RestoreProductUuidWithoutTail($uuidWithTail){
        if(strlen($uuidWithTail)>36){
            // 因为uuid的长度是36, 如果uuid换了, 那么对应要更新成正确的长度
            return substr($uuidWithTail,0, 36);
        }
        return $uuidWithTail;
    }
}