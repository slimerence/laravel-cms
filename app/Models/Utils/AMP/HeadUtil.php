<?php

namespace App\Models\Utils\AMP;

use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\Thing;

class HeadUtil
{
    private static $instance = null;
    private $schemas = null;

    private function __construct(){
        $this->schemas = $this->initSchemasGenerator();
    }

    /**
     * 简单单例
     * @return HeadUtil|null
     */
    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new HeadUtil();
        }
        return self::$instance;
    }

    /**
     * 设置所有需要的schema
     * @param Thing $thing
     * @return $this
     */
    public function addSchema(Thing $thing)
    {
        $this->schemas[] = $thing;
        return $this;
    }

    /**
     * 输出
     * @param $title
     * @param null $keywords
     * @param null $description
     * @return string
     */
    public function output($title, $keywords = null, $description = null)
    {
        $head = '<meta charset="utf-8">';
        $head .= '<script async src="https://cdn.ampproject.org/v0.js"></script>';
        $head .= '<title>'.$title.'</title>';

        if($keywords){
            $head .= '<meta name="keywords" content="'.$keywords.'">';
        }

        if($description){
            $head .= '<meta name="description" content="'.$description.'">';
        }

        // get current url
        $head .= '<link rel="canonical" href="'.url()->full().'">';
        $head .= '<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">';

        foreach ($this->schemas as $schema) {
            $head .= $schema->toScript();
        }

        return $head. $this->_getAmpBoilerplateCode();
    }

    private function initSchemasGenerator(){
        return [];
    }

    private function _getAmpBoilerplateCode(){
        return '    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>';
    }
}
