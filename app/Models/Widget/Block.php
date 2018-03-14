<?php

namespace App\Models\Widget;

use App\Models\Contract\IWidget;

class Block extends BaseWidget implements IWidget
{
    protected $fillable = [
        'name',
        'short_code',
        'content',
        'type',
        'position'
    ];

    public static $TYPE_LEFT    = 1;
    public static $TYPE_RIGHT   = 2;
    public static $TYPE_GENERAL = 3;

    /**
     * 获取类型的名称
     * @param $type
     * @return string
     */
    public static function GetTypeName($type){
        $result = 'General';
        if($type == self::$TYPE_LEFT){
            $result = 'Left Side Bar';
        }

        if($type == self::$TYPE_RIGHT){
            $result = 'Right Side Bar';
        }
        return $result;
    }

    /**
     * 根据给定的type返回Blocks
     * @param $type
     * @return mixed
     */
    public static function GetBlocksByType($type){
        return self::where('type',$type)->orderBy('position','asc')->get();
    }

    /**
     * 获取系统自定义的Block
     * @return array
     */
    public static function LoadDedicateBlocks(){
        $blocks = self::whereIn('short_code',[
            'footer','floating_box'
        ])->get();
        $blocksArray = [];
        foreach ($blocks as $block) {
            $blocksArray[$block->short_code] = $block;
        }
        return $blocksArray;
    }

    //
    public function outputHtml()
    {
        // TODO: Implement outputHtml() method.
        return $this->content;
    }
}
