<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    const MODE_OFF  = 'off';    // 表示不适用
    const MODE_TEST = 'test';   // 表示测试模式
    const MODE_LIVE = 'live';   // 表示生产模式

    public $timestamps = false;
    protected $fillable = [
        'name',
        'method_id',
        'api_token_test',
        'api_token',
        'api_secret_test',
        'api_secret',
        'hook_success',
        'hook_error',
        'notes',
        'mode',
    ];

    /**
     * 持久化的方法
     * @param array $data
     */
    public static function Persistent($data){
        if(!isset($data['id']) || empty($data['id'])){
            // 添加
            self::create($data);
        }else{
            // 更新
            $id = $data['id'];
            unset($data['id']);
            self::where('id',$id)->update($data);
        }
    }
}
