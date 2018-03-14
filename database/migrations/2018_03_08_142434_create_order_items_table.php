<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('serial_number',100);
            $table->unsignedInteger('order_id');  // 关联的订单ID
            $table->unsignedInteger('operator_id')->default(0);
            $table->unsignedInteger('user_id')->nullable(); // 如果不为空, 表示有人为的干预
            $table->unsignedInteger('product_id');
            $table->string('operator_name',100)->default('n.a');  // 该流水单归属的经销商名字
            $table->string('product_name',100);   // 该流水单归属的产品名称
            $table->float('price',8,2)->default(0);
            $table->unsignedSmallInteger('quantity')->default(0);
            $table->float('subtotal',8,2);
            $table->unsignedTinyInteger('status')->default(\App\Models\Utils\OrderStatus::$PENDING);
            $table->unsignedTinyInteger('payment_type')->default(\App\Models\Utils\PaymentTool::$TYPE_PLACE_ORDER);
            $table->text('notes')->nullable();

            $table->float('discount',8,2)->default(0);
            $table->string('discount_reason')->nullable();

            $table->timestamps();
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
