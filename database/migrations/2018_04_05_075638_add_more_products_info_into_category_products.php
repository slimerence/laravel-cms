<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreProductsInfoIntoCategoryProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_products', function (Blueprint $table) {
            $table->string('product_name');         // 可以按名称检索
            $table->unsignedInteger('position');    // 可以按排序位置检索
            $table->float('price');                 // 可以按价格检索
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
