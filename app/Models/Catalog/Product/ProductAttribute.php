<?php

namespace App\Models\Catalog\Product;

use App\Models\Catalog\Product;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $fillable = [
        'name','product_attribute_set_id','type','default_value','location','position'
    ];

    public $timestamps = false;

    public function attributeSet(){
        return $this->belongsTo(ProductAttributeSet::class,'product_attribute_set_id');
    }

    /**
     * @param Product $product
     */
    public function valuesOf(Product $product){
        return AttributeValue::where('product_id',$product->id)
            ->where('product_attribute_id',$this->id)
            ->get();
    }
}
