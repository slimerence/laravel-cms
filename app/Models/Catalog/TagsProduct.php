<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class TagsProduct extends Model
{
    public $timestamps = false;
    protected $fillable = ['tag_id','product_id','product_name','position','product_name_cn'];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
