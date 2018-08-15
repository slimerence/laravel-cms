<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/6/18
 * Time: 5:06 PM
 */

namespace App\Models\Utils\Html;

use App\Models\Utils\Html\Interfaces\IInput;

class FormHelper
{
    const THEME_BULMA = 'Bulma';
    const THEME_TWITTER_BOOTSTRAP4 = 'TW4';
    const THEME_VUEJS_ELEMENT_UI = 'VueElementUI';

    /**
     * @var FormHelper 保存 FormHelper 实例
     */
    private static $INSTANCE = null;

    /**
     * @var string  当前使用的主题
     */
    private $theme;

    /**
     * @var IInput
     */
    private $iInput;

    /**
     * 构造函数， 默认使用bulma主题
     * FormHelper constructor.
     */
    private function __construct()
    {

    }

    /**
     * 获取帮助类单例的静态方法
     * @param null $themeName
     * @return FormHelper
     */
    public static function getInstance($themeName = null){
        if(self::$INSTANCE == null){
            self::$INSTANCE = new FormHelper();
        }
        return self::$INSTANCE->setTheme($themeName);
    }

    /**
     * 设置应该使用的主题
     * @param $themeName
     * @return FormHelper
     */
    public function setTheme($themeName = null){
        if(is_null($themeName)){
            // 先使用 .evn 中规定的主题
            $themeName = env('FRONTEND_THEME',self::THEME_BULMA);
        }
        $this->theme = $themeName;
        return $this;
    }

    /**
     * 获取Input的帮助工具类
     * @return IInput
     */
    public function getInputHelper(){
        if(!$this->iInput){
            // 如果还没有实例化, 那么先实例化它
            $this->iInput = InstanceFactory::GetWorker()->buildInputHelper($this->theme);
        }
        return $this->iInput;
    }
}