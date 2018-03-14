<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 14/11/17
 * Time: 12:13 PM
 */

namespace App\Models\Utils;


class ColourTool
{
    public static $TYPE_TEXT = 1;           // 表示颜色通过文字来展示
    public static $TYPE_HEX_CODE = 2;       // 表示颜色通过代码来展示
    public static $TYPE_IMAGE = 3;          // 表示颜色通过图片来展示

    public static $COLOR_DEFAULT = '#409EFF'; // 随便指定的一个默认的颜色

    /**
     * @return array
     */
    public static function AllTypes()
    {
        return [
            self::$TYPE_TEXT        => 'Text',
            self::$TYPE_HEX_CODE    => 'HEX code',
            self::$TYPE_IMAGE       => 'Image',
        ];
    }
}