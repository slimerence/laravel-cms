<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');

            $table->string('serial_number',12)->unique();  // 订单号 out_trade_no, 用来给人读的, 保证也是唯一的 6位数字-机器id
            $table->string('transaction_reference',60)->nullable(); // 支付网关的订单流水号

            // 表示是哪个商户的订单
            $table->unsignedInteger('operator_id')->default(1);
            $table->unsignedInteger('user_id'); // 表示哪个客户的订单
            $table->string('place_order_number',50)->default('n.a');  // Place Order的订单号
            $table->string('operator_name',100)->default('n.a');  // 该订单归属的经销商名字

            $table->float('total',8,2); // 总金额
            $table->float('delivery_charge',8,2)->default(0); // 额外收取的邮寄费用

            $table->unsignedTinyInteger('status')->default(\App\Models\Utils\OrderStatus::$PENDING);
            $table->unsignedTinyInteger('payment_type')->default(\App\Models\Utils\PaymentTool::$TYPE_PLACE_ORDER);

            $table->text('notes')->nullable();
            $table->float('discount',8,2)->default(0);
            $table->string('discount_reason',255)->nullable();
            $table->unsignedSmallInteger('day');
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('hour');
            $table->unsignedTinyInteger('type')->default(1);  // 暂时没用
            $table->string('secret_code',6)->nullable();  // 取货码
            $table->string('prepay_id')->nullable();      // 在订单完整之后, 从支付网关取回支付二维码网址后的 ID

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
