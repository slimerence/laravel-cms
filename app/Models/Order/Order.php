<?php

namespace App\Models\Order;

use App\Models\Utils\OrderStatus;
use App\Models\Utils\PaymentTool;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Gloudemans\Shoppingcart\Cart;
use Ramsey\Uuid\Uuid;
use DB;
use App\Models\Group;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'serial_number','operator_id',
        'user_id','operator_name','total','delivery_charge',
        'item_amount','status','payment_type','notes','discount','discount_reason',
        'day','month','year','hour','type','secret_code','uuid','place_order_number','transaction_reference'
    ];

    /**
     * 该订单属于哪个客户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(){
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * 根据订单的uuid获取数据
     * @param $uuid
     * @return mixed
     */
    public static function GetByUuid($uuid){
        return self::where('uuid',$uuid)->first();
    }

    /**
     * 订单的具体项目
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    /**
     * 获取订单所有费用的总和
     * @return mixed
     */
    public function getTotalFinal(){
        return $this->total + $this->delivery_charge;
    }

    /**
     * 将订单切换为发票已开状态
     * @param $id
     * @return mixed
     */
    public static function IssueInvoice($id){
        return self::where('id',$id)->update([
            'status'=>OrderStatus::$INVOICED
        ]);
    }

    /**
     * 批准订单的方法. 如果给定了第三方支付的订单号, 也会一并保存下来
     * @param null $transactionReference
     * @return bool
     */
    public function approve($transactionReference=null){
        DB::beginTransaction();
        $this->status = OrderStatus::$APPROVED;
        $this->transaction_reference = $transactionReference;
        $this->save();
        $result = OrderItem::where('order_id',$this->id)
            ->update([
                'status'=>OrderStatus::$APPROVED
            ]);
        if($result){
            DB::commit();
            return $result;
        }else{
            DB::rollback();
            return false;
        }
    }

    /**
     * 拒绝订单的方法
     * @param null $notes
     * @return bool
     */
    public function decline($notes = null){
        DB::beginTransaction();
        $this->status = OrderStatus::$DECLINED;

        // 保留拒绝的理由
        $this->notes .= '<br><div class="declined-notes">Reason of Decline: ' . $notes.'</div>';
        $this->save();
        $result = OrderItem::where('order_id',$this->id)
            ->update(['status'=>OrderStatus::$DECLINED]);
        if($result){
            DB::commit();
            return $result;
        }else{
            DB::rollback();
            return false;
        }
    }

    /**
     * 更新订单的状态为付款完成
     * @param $transactionReference
     * @param $identify
     * @param string $fieldName
     * @return null/Order
     */
    public static function OrderPaymentConfirmedBy($transactionReference, $identify, $fieldName='serial_number'){
        $order = self::where($fieldName,$identify)->orderBy('id','desc')->first();
        if($order){
            $order->approve($transactionReference);
            return $order;
        }
        return null;
    }

    /**
     * 保存 Place Order 类型订单的数据
     * @param User $customer
     * @param Cart $cart
     * @param string $placeOrderNumber
     * @param string $notes
     * @param int $paymentMethod
     * @return null
     */
    public static function PlaceOrder($customer,Cart $cart,$placeOrderNumber,$notes, $paymentMethod=null){
        $now = Carbon::now();

        DB::beginTransaction();
        $orderData = [
            'serial_number'=>self::_generalOutTradeNo($customer->id),
            'user_id'=>$customer->id,
            'total'=>self::_calculateTotal($cart),
            // 计算可能产生的额外邮寄费用
            'delivery_charge'=>Group::CalculateDeliveryCharge($customer, $cart->total()),
            'status'=>OrderStatus::$PENDING,
            'payment_type'=>$paymentMethod ? $paymentMethod : PaymentTool::$TYPE_PLACE_ORDER,
            'day'=>$now->day,
            'month'=>$now->month,
            'year'=>$now->year,
            'hour'=>$now->hour,
            'place_order_number'=>strtoupper($placeOrderNumber),  // 这个项目字母永远大写, 为了搜索
            'uuid'=>Uuid::uuid4()->toString(),
            'notes'=>$notes
        ];

        $order = self::create($orderData);
        if($order){
            $dataOrderItems = $cart->content();
            $orderTotal = 0;
            foreach ($dataOrderItems as $key=>$dataOrderItem) {
                // 这里的 Order Item 是订单的每个子项, 不是具体的options, 别忘了
                $subTotal = OrderItem::Persistent($order,$dataOrderItem,config('app.name'),$key);
                if($subTotal){
                    $orderTotal += $subTotal;
                }
            }

            if($orderTotal){
                if($orderTotal != $order->total){
                    $order->total = $orderTotal;
                    $order->save();
                }
                DB::commit();
            }else{
                DB::rollBack();
                $order = null;
            }
        }else{
            DB::rollBack();
        }

        return $order;
    }

    /**
     * 计算购物车中所有的物品的总金额
     * @param Cart $cart
     * @return string
     */
    private static function _calculateTotal(Cart $cart){
        $total = str_replace(',','',$cart->total());
        return $total;
    }

    /**
     * 产生微信等支付系统所需要的 out_trade_no 值的方法
     * 商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*@ ，且在同一个商户号下唯一
     * @param $userId
     * @return string
     */
    private static function _generalOutTradeNo($userId){
        return random_int(100000,999999).'-'.$userId; // 由于 $userId 的存在, 肯定这个值是唯一的
    }

    /**
     * 一个生成对自己的描述的方法
     * @return string
     */
    public function getOrderDescription(){
        return 'This is the order description: '.$this->uuid;
    }
}
