<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 10/10/17
 * Time: 9:34 PM
 */

namespace App\Models\Utils;

use Storage;

class MediaTool
{
    public static $TYPE_IMAGE = 1;
    public static $TYPE_AUDIO = 2;
    public static $TYPE_VIDEO = 3;
    public static $TYPE_DOWNLOADABLE = 4;
    public static $TYPE_PDF   = 5;

    /**
     *  表示media是针对哪类对象的
     */
    public static $FOR_GENERAL  = 0;    // 无针对的类型
    public static $FOR_PRODUCT  = 1;
    public static $FOR_CATEGORY = 2;
    public static $FOR_CUSTOMER = 3;
    public static $FOR_GALLERY  = 4;    // 针对 Gallery 类型

    /**
     * 根据给定的扩展名， 猜测可能的文件类型
     * @param $extensionName
     * @return int
     */
    public static function GuessFileTypeByExtensionName($extensionName){
        $extensionName = strtolower($extensionName);
        if($extensionName == 'mp4' || $extensionName == 'avi' || $extensionName == 'flv'){
            return self::$TYPE_VIDEO;
        }elseif ($extensionName == 'mp3'){
            return self::$TYPE_AUDIO;
        }elseif ($extensionName == 'jpg' || $extensionName == 'png' || $extensionName == 'jpeg' || $extensionName == 'gif'){
            return self::$TYPE_IMAGE;
        }elseif ($extensionName == 'pdf'){
            return self::$TYPE_PDF;
        }
        return self::$TYPE_DOWNLOADABLE;
    }

    /**
     * 获取给定文件的大小
     * @param string $filePath
     * @return float|int
     */
    public static function GetFileSizeInKB($filePath){
        $filePath = str_replace('/storage','',$filePath);
        if(Storage::exists($filePath)){
            // 如果文件存在
            return ceil(Storage::size($filePath)/1000);
        }
        return 0;
    }
}