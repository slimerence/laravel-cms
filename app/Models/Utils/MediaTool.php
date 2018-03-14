<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 10/10/17
 * Time: 9:34 PM
 */

namespace App\Models\Utils;


class MediaTool
{
    public static $TYPE_IMAGE = 1;
    public static $TYPE_AUDIO = 2;
    public static $TYPE_VIDEO = 3;
    public static $TYPE_DOWNLOADABLE = 4;

    /**
     *  表示media是针对哪类对象的
     */
    public static $FOR_PRODUCT  = 1;
    public static $FOR_CATEGORY = 2;
    public static $FOR_CUSTOMER = 3;
    public static $FOR_GALLERY  = 4;        // 针对 Gallery 类型
}