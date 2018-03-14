<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');

            // 产品的类型, 默认为通用型
            $table->unsignedTinyInteger('type')->default(\App\Models\Utils\ProductType::$GENERAL_ITEM);
            // 产品所归属的属性集,默认为0, 表示是基本产品
            $table->unsignedTinyInteger('attribute_set_id')->default(\App\Models\Utils\ProductType::$BASIC_PRODUCT_ATTRIBUTE_SET);
            // 产品要求的最小订货数量
            $table->unsignedSmallInteger('min_quantity')->default(0);

            // 产品的库存, 默认为0, 表示库存无限
            $table->unsignedMediumInteger('stock')->default(0);
            $table->boolean('manage_stock')->default(false);

            // 是否为推荐产品
            $table->boolean('promote')->default(false);

            // 目录的排序
            $table->unsignedSmallInteger('position')->default(0);

            // 如果是某个用户组专属的,那么这里Group的ID, 否则是通用产品, 默认值为0
            $table->unsignedInteger('group_id')->default(0);


            $table->string('name',100);
            $table->string('sku',100);
            $table->string('uri',255);

            // 产品的默认价格
            $table->float('default_price',8,2)->default(0);
            // 产品的特价
            $table->float('special_price',8,2)->default(0);

            // 产品的GST
            $table->unsignedSmallInteger('tax')->default(10);
            $table->string('unit_text')->nullable();  // 产品出售的单位,比如一个, 一打, Carton等等. 默认是一个

            $table->string('image_path')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->text('keywords')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('name_cn',100)->nullable();

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
        Schema::dropIfExists('products');
    }
}
