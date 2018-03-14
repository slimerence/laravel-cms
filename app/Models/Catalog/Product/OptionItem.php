<?php

namespace App\Models\Catalog\Product;

use Illuminate\Database\Eloquent\Model;

class OptionItem extends Model
{
    protected $fillable = [
        'product_option_id','label','extra_value'
    ];

    public $timestamps = false;

    /**
     * 持久化选项记录
     * @param ProductOption |  $productOption
     * @param $data
     * @return mixed
     */
    public static function Persistent($productOption, $data){
        $optionId = $productOption;
        if(!is_integer($productOption)){
            $optionId = $productOption->id;
        }

        if(isset($data['id']) && !empty($data['id'])){
            // 更新的操作
            $oItem = self::find($data['id']);
            if($oItem){
                $oItem->label = $data['label'];
                $oItem->extra_value = $data['extra_value'];
                return $oItem->save();
            }else{
                return null;
            }
        }

        // 添加新的记录的操作
        return self::create([
            'product_option_id'=>$optionId,
            'label'=>$data['label'],
            'extra_value'=>$data['extra_value'],
        ]);
    }

    /**
     * 克隆选项记录
     * @param ProductOption |  $productOption
     * @param $data
     * @return mixed
     */
    public static function DoClone($productOption, $data){
        $optionId = $productOption;
        if(!is_integer($productOption)){
            $optionId = $productOption->id;
        }

        // 添加新的记录的操作, 就是给了什么, 插入什么
        return self::create([
            'product_option_id'=>$optionId,
            'label'=>$data['label'],
            'extra_value'=>$data['extra_value'],
        ]);
    }

    /**
     * 确认某个产品的选项的值存在. 如果存在, 则返回该记录
     * @param $id
     * @param $productOptionId
     * @return mixed
     */
    public static function Exist($id, $productOptionId){
        return self::where('id', $id)->where('product_option_id',$productOptionId)->first();
    }
}
