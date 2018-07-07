<?php

namespace App\Models\Settings;

use App\Models\Utils\PaymentTool;
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

    /**
     * 获取所有的有效支付方式, 只要不是Off即可
     * @return mixed
     */
    public static function GetAllAvailable(){
        return self::where('mode','<>',PaymentMethod::MODE_OFF)->get();
    }

    /**
     * 获取Stripe的 secret
     * @return string
     */
    public static function GetStripeSecret(){
        $method = self::GetByMethodId(PaymentTool::$TYPE_STRIPE);
        return $method->getApiSecret();
    }

    /**
     * @param $methodId
     * @return PaymentMethod
     */
    public static function GetByMethodId($methodId){
        return self::where('method_id',$methodId)->first();
    }

    /**
     * 根据当前的mode自动返回
     * @return string
     */
    public function getApiToken(){
        return $this->isLiveMode() ?
            $this->api_token:
            $this->api_token_test;
    }

    /**
     * 根据当前的mode自动返回对应的secret
     * @return string
     */
    public function getApiSecret(){
        return $this->isLiveMode() ?
            $this->api_secret:
            $this->api_secret_test;
    }

    /**
     * 是否为生产模式
     * @return bool
     */
    public function isLiveMode(){
        return $this->mode === self::MODE_LIVE;
    }

    /**
     * 是否为测试模式
     * @return bool
     */
    public function isTestMode(){
        return $this->mode === self::MODE_TEST;
    }
}
