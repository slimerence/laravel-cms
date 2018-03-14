<?php

namespace App\Models\Catalog\Product;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeSet extends Model
{
    protected $fillable = [
        'name','parent_id'
    ];

    public $timestamps = false;

    public function parent(){
        return $this->belongsTo(self::class,'parent_id');
    }

    public function kids(){
        return $this->hasMany(self::class,'parent_id');
    }

    public function attributes(){
        return $this->hasMany(ProductAttribute::class);
    }

    public static function GetName($id){
        $as = self::find($id);
        return $as ? $as->name : 'n.a';
    }
}
