<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = [
        'name','image_url','status','promotion','keywords','seo_description','extra_html'
    ];

    protected $casts = [
        'status' => 'boolean',
        'promotion' => 'boolean',
    ];

    public static function Persistent($data){
        return self::create($data);
    }

    /**
     * 获取品牌的图片URL
     * @return bool|string
     */
    public function getImageUrl(){
        return $this->image_url ? asset('storage/'.$this->image_url) : false;
    }

    /**
     * 品牌产品的子系列
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serials(){
        return $this->hasMany(BrandSerial::class);
    }
}
