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
            return DeliveryFee::CalculateFee(
                $customer->group_id,
                $orderAmount,
                $customer->country,
                $customer->state,
                $customer->postcode,
                $totalWeight
            );
//            if($customer->country == 'Australia'){
//                if($orderAmount >= intval(env('ORDER_MIN_TOTAL_FOR_FREE_DELIVERY',config('system.ORDER_MIN_TOTAL_FOR_FREE_DELIVERY')))){
//                    // 如果是澳洲境内, 订单金额超过了一定的值, 那么就免运费了
//                    return 0;
//                }else{
//                    if( $customer->state == env('FREE_DELIVERY_STATE')){
//                        // 如果是免费洲的, 免运费
//                        return 0;
//                    }else{
//                        // 如果不是新洲的并且订单总额没有超过最低限制, 那么就要额外付运费了
//                        return env('DOMESTIC_DELIVERY_FEE',config('system.DOMESTIC_DELIVERY_FEE'));
//                    }
//                }
//            }else{
//                // 不是澳洲境内的, 海外最低运费收取金额
//                return env('OVERSEA_DELIVERY_FEE',config('system.OVERSEA_DELIVERY_FEE'));
//            }
        }else{
            // 未登陆用户, 返回 -1
            return -1;
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
