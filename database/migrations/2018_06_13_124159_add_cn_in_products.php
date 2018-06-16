<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCnInProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 添加中文信息
         */
        Schema::table('products', function (Blueprint $table) {
            $table->text('short_description_cn')->nullable();
            $table->text('keywords_cn')->nullable();
            $table->text('seo_description_cn')->nullable();
            $table->longText('description_cn')->nullable();
        });

        Schema::table('category_products', function (Blueprint $table) {
            $table->string('product_name_cn')->nullable();
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
