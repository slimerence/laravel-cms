<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class RelatedProduct extends Model
{
    protected $fillable = ['product_id','related_products_id'];

    public $timestamps = false;

    /**
     * 关联的产品
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(){
        return $this->belongsTo(Product::class);
    }

    /**
     * 加载和当前产品相关的产品的方法
     * @param bool $idAndNameOnly
     * @return array
     */
    public function load($idAndNameOnly = false){
        if(is_null($this->related_products_id)){
            return [];
        }
        else{
            $ids = explode(',',$this->related_products_id);
            return $idAndNameOnly ? Product::select('id','name')->whereIn('id',$ids)->get() : Product::whereIn('id',$ids)->get();
        }
    }

    /**
     * 保存关联产品的操作
     * @param $productId
     * @param $postData
     * @return mixed
     */
    public static function Persistent($productId, $postData){
        $rp = self::where('product_id',$productId)->first();

        $idsString = null;
        $ids = [];
        foreach ($postData as $item) {
            $ids[] = $item['id'];
        }

        if(!empty($ids)){
            $idsString = implode(',',$ids);
        }

        // 保存或者更新
        if($rp){
            $rp->related_products_id = $idsString;
            return $rp->save();
        }else{
            return self::create([
                'product_id'=>$productId,
                'related_products_id'=>$idsString
            ]);
        }
    }
}
