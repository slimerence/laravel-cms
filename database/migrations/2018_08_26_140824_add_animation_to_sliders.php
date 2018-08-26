<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnimationToSliders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('slider_images', function (Blueprint $table) {
            // 添加animate的动画类名字段
            $table->string('animate_class')->nullable();//进入动画
            $table->string('animate_class_out')->nullable();// 退出动画
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
