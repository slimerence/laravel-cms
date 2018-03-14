<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    public $timestamps = false;
    protected $fillable = ['category_id','product_id'];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
