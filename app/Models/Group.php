<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Class Group 用户组
 * @package App\Models
 */
class Group extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name','phone','address','city','state','postcode',
        'country','has_min_order_amount','shipping_fee',
        'fax','status','extra','email'
    ];

    /**
     * Persistent Group Data
     * @param $data
     * @return mixed
     */
    public static function Persistent($data){
        return self::create($data);
    }

    /**
     * 计算额外的邮寄费用
     * @param User $customer
     * @param $orderAmount
     * @return int
     */
    public static function CalculateDeliveryCharge(User $customer, $orderAmount){
        if($customer){
            if($customer->country == 'Australia'){
                // 如果是澳洲境内
                if($customer->state !== 'VIC'){
                    // 如果不是新洲的并且订单总额没有超过最低限制, 那么就要额外付运费了
                    return 0;
                }else{
                    // VIC 的不付钱
                    return 0;
                }
            }else{
                // 不是澳洲境内的, 海外最低运费收取金额
                return 55;
            }
        }
    }

    /**
     * 返回经销商地址
     * Return group address text
     * @return string
     */
    public function getAddressText(){
        return $this->address ?
            $this->address.', '.$this->city.' '.$this->postcode.', '.$this->state.' '.$this->country
            : null;
    }
}
