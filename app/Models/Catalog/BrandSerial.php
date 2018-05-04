<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class BrandSerial extends Model
{
    protected $fillable = [
        'brand_id','name','keyword'
    ];
    public $timestamps = false;

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
