<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            $table->boolean('status')->default(true);
            $table->unsignedTinyInteger('role')->default(1);
            $table->unsignedTinyInteger('group_id')->default(1);
            $table->string('phone',20)->nullable();

            $table->string('address',100)->nullable();
            $table->string('city',30)->nullable();
            $table->string('postcode',10)->nullable();
            $table->string('state',20)->nullable();
            $table->string('country',30)->nullable();

            $table->string('fax',20)->nullable();

            // 支付相关
            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
