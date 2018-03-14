<?php

namespace App\Models\Catalog\Product;

use App\Models\Catalog\Product;
use App\Models\Utils\OptionTool;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $fillable = [
        'product_id','name','type','position'
    ];

    public $timestamps = false;

    /**
     * 取得一对多的那个关系数据集合
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(){
        return $this->hasMany(OptionItem::class);
    }

    /**
     * 取得一对一的那个关系数据值
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function item(){
        return $this->hasOne(OptionItem::class);
    }

    public static function Details($productId){
        $options = self::where('product_id',$productId)->get();
        $result = [];
        foreach ($options as $option) {
            $option->items = $option->items->toArray();
            $result[] = $option->toArray();
        }

        return $result;
    }

    /**
     * 根据给定的产品id清空所有的option以及option的值
     * @param $productId
     */
    public static function TerminateByProduct($productId){
        $options = self::where('product_id',$productId)->get();
        foreach ($options as $option) {
            OptionItem::where('product_option_id',$option->id)->delete();
            $option->delete();
        }
    }

    /**
     * @param Product | Integer $product
     * @param array $data
     * @param array | null $items
     * @return ProductOption | bool
     */
    public static function Persistent($product, $data, $items=null){
        $productOption = false;
        $productId = $product;
        if(!is_integer($product)){
            $productId = $product->id;
        }

        $optionItems = $items;
        if(empty($optionItems) && isset( $data['items']) && is_array($data['items']) && count($data['items'])>0 ){
            $optionItems = $data['items'];
        }

        if(!empty($optionItems)){
            if(isset($data['id']) && !empty($data['id'])){
                // 如果设定了一个id,那么就是更新的操作
                $productOption = self::find($data['id']);
                if($productOption){
                    $productOption->name = $data['name'];
                    $productOption->type = $data['type'];
                    $productOption->save();
                }else{
                    return null;
                }
            }else{
                // 没有给一个id来, 那么就是增加的操作
                $productOption = self::create([
                    'product_id'=>$productId,
                    'name'=>$data['name'],
                    'type'=>$data['type'],
                    'position'=>0
                ]);
            }

            if($productOption){
                if($productOption->type == OptionTool::$TYPE_TEXT && isset($optionItems[0])){
                    OptionItem::Persistent($productOption, $optionItems[0]);
                }elseif($productOption->type == OptionTool::$TYPE_DROP_DOWN || $productOption->type == OptionTool::$TYPE_RADIO_BUTTON){
                    foreach ($optionItems as $optionItem) {
                        OptionItem::Persistent($productOption, $optionItem);
                    }
                }
            }
        }
        return $productOption;
    }

    /**
     * 克隆产品Option的专用方法
     * @param Product | Integer $product
     * @param array $data
     * @param array | null $items
     * @return ProductOption | bool
     */
    public static function DoClone($product, $data, $items=null){
        $productOption = false;
        $productId = $product;
        if(!is_integer($product)){
            $productId = $product->id;
        }

        $optionItems = $items;
        if(empty($optionItems) && isset( $data['items']) && is_array($data['items']) && count($data['items'])>0 ){
            $optionItems = $data['items'];
        }

        if(!empty($optionItems)){
            if(isset($data['id']) && !empty($data['id'])){
                // 因为克隆操作,如果有了id 表示是更新, 那什么也不能做
            }else{
                // 没有给一个id来, 那么就是增加的操作
                $productOption = self::create([
                    'product_id'=>$productId,
                    'name'=>$data['name'],
                    'type'=>$data['type'],
                    'position'=>$data['position']
                ]);
            }

            if($productOption){
                if($productOption->type == OptionTool::$TYPE_TEXT && isset($optionItems[0])){
                    OptionItem::DoClone($productOption, $optionItems[0]);
                }else{
                    foreach ($optionItems as $optionItem) {
                        OptionItem::DoClone($productOption, $optionItem);
                    }
                }
            }
        }
        return $productOption;
    }

    /**
     * 根据给定的id和product id返回选项
     * @param $id
     * @param $productId
     * @return mixed
     */
    public static function Exist($id, $productId){
        return self::where('id',$id)->where('product_id', $productId)->first();
    }
}
