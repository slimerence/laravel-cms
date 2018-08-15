<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class GroupProduct extends Model
{
    protected $fillable = [
        'product_id',
        'grouped_product_id',
        'quantity',
        'notes',
        'color',
        'options',
    ];

    public $timestamps = false;

    /**
     * 关联的产品
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function relatedProduct(){
        return $this->hasOne(Product::class,'id','grouped_product_id');
    }
}
