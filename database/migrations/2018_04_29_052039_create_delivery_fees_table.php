<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_fees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country')->default(\App\Models\Shipment\DeliveryFee::COUNTRY_AU); // 目标国家
            $table->string('state')->nullable();    // 目标省份
            $table->string('postcode')->nullable();     // 目标postcode
            $table->float('basic')->default(5);     // 基础价格
            $table->float('price_per_kg')->default(0); // 基础价格之外的每公斤运费
            $table->text('formula')->nullable();    // 计算公式
            // 此记录针对的目标客户群
            $table->unsignedMediumInteger('target_customer_group')->default(\App\Models\Utils\UserGroup::$GENERAL_CUSTOMER);
            // 此记录生效的最少订单金额
            $table->unsignedMediumInteger('min_order_total')->default(0);
            // 此记录生效与否
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_fees');
    }
}
