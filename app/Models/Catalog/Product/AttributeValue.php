<?php

namespace App\Models\Catalog\Product;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $fillable = [
        'product_id','product_attribute_id','value','notes'
    ];

    public $timestamps = false;

    /**
     * 持久化产品属性数据
     * @param $product
     * @param $data
     */
    public static function Persistent($product, $data){
        $productId = $product;
        if(!is_integer($product)){
            $productId = $product->id;
        }

        // 总体原则为, 先查找, 如果找到就更新,如果没有找就创建新的
        $pavs = self::where('product_id',$productId)
            ->where('product_attribute_id',$data['product_attribute_id'])
            ->get();

        if(count($pavs) > 0){
            if(count($pavs) == 1){
                $pav = $pavs[0];
                // 找到了,那么就更新value
                $pav->value = trim($data['value']);
                $pav->save();
            }else{
                // 对应了多个值。 那么全部删除, 然后重新生成
                if(
                self::where('product_id',$productId)
                    ->where('product_attribute_id',$data['product_attribute_id'])
                    ->delete()
                ){
                    if(is_array($data['value'])){
                        // 如果提交的是数组
                        foreach ($data['value'] as $value) {
                            // 并且数组的值不是空的
                            if(!empty($value)){
                                self::create([
                                    'product_id'=>$productId,
                                    'product_attribute_id'=>$data['product_attribute_id'],
                                    'value'=>$value['url'],
                                    'notes'=>$value['name']
                                ]);
                            }
                        }
                    }elseif(is_string($data['value'])){
                        self::create([
                            'product_id'=>$productId,
                            'product_attribute_id'=>$data['product_attribute_id'],
                            'value'=>$data['value']
                        ]);
                    }
                }
            }

        }else{
            // 如果没有找就创建新的
            if(is_array($data['value'])){
                // 如果提交的是数组
                foreach ($data['value'] as $value) {
                    // 并且数组的值不是空的
                    if(!empty($value)){
                        self::create([
                            'product_id'=>$productId,
                            'product_attribute_id'=>$data['product_attribute_id'],
                            'value'=>$value['url'],
                            'notes'=>$value['name']
                        ]);
                    }
                }
            }elseif(is_string($data['value'])){
                self::create([
                    'product_id'=>$productId,
                    'product_attribute_id'=>$data['product_attribute_id'],
                    'value'=>$data['value']
                ]);
            }
        }
    }

    public static function GetByProduct($product){
        $productId = $product;
        if(!is_integer($product)){
            $productId = $product->id;
        }

        return self::where('product_id',$product->id)->get();
    }
}
