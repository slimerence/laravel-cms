<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 29/10/17
 * Time: 2:19 PM
 */

namespace App\Models\Utils;


class ContentTool
{
    public static $LAYOUT_THREE_COLUMNS     = 4;
    public static $LAYOUT_TWO_COLUMNS_LEFT  = 3;
    public static $LAYOUT_TWO_COLUMNS_RIGHT = 2;
    public static $LAYOUT_ONE_COLUMN        = 1;

    /**
     * 根目录的ID
     * @var int
     */
    public static $ROOT_MENU_ID = 1;
    public static $ROOT_CONFIGURATION_ID = 1;

    public static $newlineFilters = ['seo_keyword','keywords','seo_description','css_class','link_to','short_description','content','description','teasing'];

    /**
     * 表示page的content中是否有动态内容, 主要就是是否嵌入了BLOCK
     * @var bool
     */
    public static $CONTENT_TYPE_STATIC        = 1;
    public static $CONTENT_TYPE_DYNAMIC       = 2;

    public static $FACEBOOK_LINK = 1;
    public static $TWITTER_LINK = 2;
    public static $LINKED_IN_LINK = 3;
    public static $INSTAGRAM_LINK = 4;
    public static $PINTEREST_LINK = 5;
    public static $WEIBO_LINK = 6;
    public static $WECHAT_LINK = 7;
    public static $GOOGLE_PLUS_LINK = 8;

    /**
     * 对某些特定的字符串进行去除 newline 符号的工作
     * @param $data
     * @return array
     */
    public static function RemoveNewLine($data){
        $newData = [];
        foreach ($data as $key=>$value) {
            if(in_array($key,self::$newlineFilters)){
                $value = str_replace("'",'&apos;',$value);
                $newData[$key] = self::RemoveNewLineFromString($value);
            }else{
                $newData[$key] = $value;
            }
        }
        return $newData;
    }

    /**
     * 针对给定的字符串移除 Newline 字符
     * @param $string
     * @return mixed
     */
    public static function RemoveNewLineFromString($string){
        return str_replace(PHP_EOL,'',$string);
    }

    /**
     * 获取页面所有布局
     * @return array
     */
    public static function GetPageLayoutTypes(){
        return [
            self::$LAYOUT_ONE_COLUMN            => 'One Column',
            self::$LAYOUT_TWO_COLUMNS_LEFT      => 'Two Columns',
            self::$LAYOUT_TWO_COLUMNS_RIGHT     => 'Two Columns with Right Sidebar',
            self::$LAYOUT_THREE_COLUMNS         => 'Three Columns',
        ];
    }

    /**
     * 获取页面所有类型
     * @return array
     */
    public static function GetPageContentTypes(){
        return [
            self::$CONTENT_TYPE_STATIC => 'Static Content',
            self::$CONTENT_TYPE_DYNAMIC => 'Dynamic Content'
        ];
    }

    /**
     * 获取社交媒体所有类型
     * @return array
     */
    public static function GetSocialNetworkTypes(){
        return [
            self::$FACEBOOK_LINK => 'Facebook',
            self::$TWITTER_LINK => 'Twitter',
            self::$LINKED_IN_LINK => 'LinkedIn',
            self::$INSTAGRAM_LINK => 'Instagram',
            self::$PINTEREST_LINK => 'Pinterest',
            self::$WEIBO_LINK => 'WeiBo 微博',
            self::$WECHAT_LINK => 'WeChat 微信',
            self::$GOOGLE_PLUS_LINK => 'Google +',
        ];
    }

    /**
     * 根据给定的Key获取社交媒体名称
     * @param $key
     * @return mixed|string
     */
    public static function GetSocialNetworkByKey($key){
        $types = self::GetSocialNetworkTypes();
        return isset($types[$key]) ? $types[$key] : '';
    }

    /**
     * 根据给定的Key获取Page类型名称
     * @param $key
     * @return mixed|string
     */
    public static function GetPageContentTypeByKey($key){
        $types = self::GetPageContentTypes();
        return isset($types[$key]) ? $types[$key] : '';
    }

    /**
     * 根据给定的Key获取Page布局名称
     * @param $key
     * @return mixed|string
     */
    public static function GetPageLayoutTypeByKey($key){
        $types = self::GetPageLayoutTypes();
        return isset($types[$key]) ? $types[$key] : '';
    }
}