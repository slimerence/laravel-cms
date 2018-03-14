<?php

namespace App\Models\Catalog\Product;

use App\Models\Catalog\Product;
use App\Models\Utils\ColourTool;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Colour extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id','type','name','value','extra_money','imageUrl'
    ];

    /**
     * 存储产品颜色数据
     * @param Product $product
     * @param $data
     * @return mixed
     */
    public static function DoClone(Product $product, $data){
        $bean = [
            'product_id'=>$product->id,
            'name'=>$data['name'],
            'type'=>$data['type'],
            'value'=>$data['value'],
            'extra_money'=>$data['extra_money'],
            'imageUrl'=>$data['imageUrl'],
        ];

        return self::create($bean);
    }

    /**
     * 存储产品颜色数据
     * @param Product $product
     * @param $data
     * @return mixed
     */
    public static function Persistent(Product $product, $data){
        $bean = [
            'product_id'=>$product->id,
            'name'=>$data['name'],
            'type'=>$data['type'],
            'value'=>$data['value'],
            'extra_money'=>$data['extra_money'],
            'imageUrl'=>$data['imageUrl'],
        ];
        if(isset($data['id']) && $data['id']){
            // 更新的操作
            return self::where('id',$data['id'])
                ->update($bean);
        }else{
            return self::create($bean);
        }
    }

    /**
     * 删除颜色, 如果有关联的图片, 也一起删除
     * @param $id
     * @return bool
     */
    public static function Terminate($id){
        $colour = self::find($id);
        if($colour){
            // 删除关联的文件, 如果有必要
            if($colour->type == ColourTool::$TYPE_IMAGE && $colour->imageUrl){
                Storage::delete($colour->imageUrl);
            }
            return $colour->delete();
        }
        return true;
    }

    /**
     * 根据给定产品id加载颜色数据
     * @param $productId
     * @param $hideProductId
     * @return mixed
     */
    public static function LoadByProduct($productId, $hideProductId = false){
        $colours = null;
        if($hideProductId){
            $colours = self::select('id','type','name','value','extra_money','imageUrl')
                ->where('product_id',$productId)
                ->get();
        }else{
            $colours = self::where('product_id',$productId)
                ->get();
        }

        /**
         * 保证Colour记录的value字段, 无论如何要返回一个值。如果这个值本来是空的, 那么就返回name的值.
         * 这样的处理之后, 可以保证前端的colour可以正常的渲染
         */
        foreach ($colours as $colour) {
            if(empty($colour->value)){
                $colour->value = $colour->name;
            }
        }
        return $colours;
    }
}
