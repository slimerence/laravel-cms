<?php

namespace App\Models;

use App\Models\Shipment\DeliveryFee;
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
     * 计算额外的邮寄费用. 如果没有给定用户, 那么运费返回无效的 -1
     * @param User $customer
     * @param int $orderAmount
     * @param float $totalWeight
     * @return int
     */
    public static function CalculateDeliveryCharge(User $customer=null, $orderAmount, $totalWeight = 0.0){
        if($customer){
            // 登录用户
            return DeliveryFee::CalculateFee(
                $customer->group_id,
                $orderAmount,
                $customer->country,
                $customer->state,
                $customer->postcode,
                $totalWeight
            );
        }else{
            // 未登陆用户, 返回 -1
            return env('DOMESTIC_DELIVERY_FEE',0);
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
