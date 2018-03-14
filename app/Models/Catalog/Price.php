<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'product_id','name','value','tax','start_from','end_at',
        'type' // 为空表示任何支付方式都可以享受此价格
    ];

    protected $dates = [
        'start_from',
        'end_at',
    ];

    public $timestamps = false;

    /**
     * 根据给定的产品获取价格
     * @param Product $product
     * @return mixed
     */
    public static function getByProduct(Product $product){
        return self::where('product_id',$product->id)
            ->get();
    }

    public function isAvailableNow(){

    }
}
