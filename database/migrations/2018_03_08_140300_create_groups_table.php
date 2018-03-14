<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Group;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            // 该用户组有最少的订单金额要求。 默认为无最低要求
            $table->unsignedSmallInteger('min_order_amount')->default(0);
            // 未达到最低订单金额时需要附加的运费,默认为0
            $table->unsignedSmallInteger('shipping_fee')->default(0);
            $table->string('name');
            $table->boolean('status')->default(false); // 默认的group是非激活的
            $table->string('phone', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->text('extra')->nullable();  // 额外的信息
        });

        Group::Persistent([
            'name'=>'General',
            'status'=> true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
