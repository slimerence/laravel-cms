<?php

namespace App\Models\Order;

use App\Models\Utils\OrderStatus;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use App\Models\Catalog\Product;

class OrderItem extends Model
{
    protected $fillable = [
        'uuid','serial_number','operator_id',
        'user_id','product_id','operator_name','product_name',
        'subtotal','quantity','price','order_id',
        'status','payment_type','notes','discount','discount_reason'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    /**
     * 持久化一个订单的Item
     * @param Order $order
     * @param CartItem $cartItem
     * @param string $operatorName
     * @param int $cartItemIndex
     * @return bool
     */
    public static function Persistent(Order $order, CartItem $cartItem, $operatorName='n.a', $cartItemIndex=0){
        $product = Product::GetByUuid($cartItem->id);

        if($product){
            $notes = '';
            $options = $cartItem->options;

            // 用来保存订单项中的产品的附加Option所带来的价格增量
            $priceExtra = 0;

            if($options && count($options)>0){
                foreach ($options as $key => $option) {
                    // 专门处理产品的Colour
                    if ($key === 'colour'){
//                        dd($option['extra_money']>0 ? ' +'.config('system.CURRENCY').number_format($option['extra_money'],2) : null);
                        $extra_money = $option['extra_money']>0 ? ' +'.config('system.CURRENCY').number_format($option['extra_money'],2) : null;
                        $name = '<span class="note-option-name">Colour: </span>';
                        $value = '<span class="note-option-value">'
                            .$option['name']
                            .$extra_money
                            .'</span>';
                        $notes .= '<p class="note-option-item">'.$name.$value.'</p>';
                    }elseif (is_array($option) && isset($option['name']) && isset($option['value'])){
                        // 处理非 Colour 的选项
                        $name = '<span class="note-option-name">'.$option['name'].'</span>';
                        $value = '<span class="note-option-value">'.$option['value'].'</span>';
                        $notes .= '<p class="note-option-item">'.$name.$value.'</p>';
                    }
                    $priceExtra += self::ParseProductOptionDataInCart($option);
                }
            }

            $priceFinal = ($product->getSpecialPriceGST()?$product->getSpecialPriceGST():$product->getDefaultPriceGST()) + $priceExtra;

            $dataOrderItem = [
                'order_id'=>$order->id,
                'uuid'=>Uuid::uuid4()->toString(),
                'serial_number'=>$order->serial_number.'-'.$cartItemIndex,
                'user_id'=>$order->user_id,
                'product_id'=>$product->id,
                'operator_name'=>$operatorName,
                'product_name'=>$product->name,
                // Use special price if possible
                'price'=>$priceFinal,
                'quantity'=>$cartItem->qty,
                'subtotal'=>$cartItem->qty * $priceFinal,
                /**
                 * 订单项的状态初始是 PENDING, 因为某个订单项有可能是无法完成的
                 */
                'status'=>OrderStatus::$PENDING,
                'payment_type'=>$order->payment_type,
                'notes'=>$notes
            ];
            $orderItem = self::create($dataOrderItem);
            if($orderItem){
                return $orderItem->subtotal;
            }else{
                return false;
            }
        }
        return false;
    }

    /**
     * 解析购物车对象中的option数组, 取出正确的附加价值浮点数然后返回
     * @param $optionData
     * @return float|int
     */
    public static function ParseProductOptionDataInCart($optionData){
        if(is_array($optionData)){
            $validValueString = null;
            if(isset($optionData['value']) && $optionData['value']){
                $validValueString =  $optionData['value'];
            }

            if(isset($optionData['extra_money']) && $optionData['extra_money']){
                $validValueString =  $optionData['extra_money'];
            }

            if($validValueString && strpos($validValueString,config('system.CURRENCY')) !== false){
                list($useless, $floatValueString) = explode(config('system.CURRENCY'), $validValueString);
                if(strlen($floatValueString)>0){
                    // 附加的价值是不可能为负值的, 如果为负值, 则返回0
                    return floatval($floatValueString)>=0 ? floatval($floatValueString) : 0;
                }else{
                    return 0;
                }
            }
        }else{
            // 不是数组, 直接返回0
            return 0;
        }
    }
}
