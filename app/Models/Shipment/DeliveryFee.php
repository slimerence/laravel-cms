<?php

namespace App\Models\Shipment;

use Illuminate\Database\Eloquent\Model;

class DeliveryFee extends Model
{
    const COUNTRY_AU = 'Australia';
    const COUNTRY_NZ = 'New Zealand';
    const COUNTRY_CN = 'China';

    public $timestamps = false;
    
    protected $fillable = [
        'country',  // 目标国家
        'state',    // 目标省份 nullable
        'postcode',     // 目标postcode nullable
        'basic',    // 基础价格 default(5)
        'price_per_kg',// 基础价格之外的每公斤运费 >default(0)
        'formula',  // 计算公式 nullable
        'target_customer_group',    // 此记录针对的目标客户群 default(\App\Models\Utils\UserGroup::$GENERAL_CUSTOMER)
        'min_order_total',  // 此记录生效的最少订单金额 default(0)
        'status',   // 此记录生效与否 default(true)
    ];

    /**
     * 获取所有的目标国家
     * @return array
     */
    public static function GetAvailableCountries(){
        $data = [
            ['value'=>self::COUNTRY_AU,'address'=>self::COUNTRY_AU],
            ['value'=>self::COUNTRY_NZ,'address'=>self::COUNTRY_NZ],
            ['value'=>self::COUNTRY_CN,'address'=>self::COUNTRY_CN],
        ];
        $result = self::select('country')
            ->groupBy('country')
            ->get();
        if(count($result)>0){
            foreach ($result as $item) {
                if(!self::_isCountryExisted($item->country, $data)){
                    $data[] = [
                        'value'=>$item->country,
                        'address'=>$item->country
                    ];
                }
            }
        }
        return $data;
    }

    /**
     * 给定的国家是否已经在给定的$data中
     * @param $country
     * @param $data
     * @return bool
     */
    private static function _isCountryExisted($country, $data){
        $result = false;
        foreach ($data as $item) {
            if($country == $item['value']){
                $result = true;
                break;
            }
        }
        return $result;
    }
}
