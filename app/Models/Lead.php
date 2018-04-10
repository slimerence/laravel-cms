<?php

namespace App\Models;

use App\Models\Catalog\Product;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'product_id',
        'user_id'
    ];

    /**
     * 关联的用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * 关联的产品
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(){
        return $this->belongsTo(Product::class);
    }

    /**
     * 保存 Lead
     * @param $leadData
     * @return mixed
     */
    public static function Persistent($leadData){
        if(isset($leadData['user']) && !empty($leadData['user'])){
            // 表示是已经登陆的用户
            $user = User::where('uuid',$leadData['user'])->first();
            $leadData['user_id'] = $user->id;
        }

        if(isset($leadData['selectedProductId']) && !empty($leadData['selectedProductId'])){
            $product = Product::where('uuid',$leadData['selectedProductId'])->first();
            $leadData['product_id'] = $product->id;
            $leadData['message'] = 'Product: '.$leadData['selectedProductName']
                .'<br>'.$leadData['message'];
        }

        return self::create($leadData);
    }
}
