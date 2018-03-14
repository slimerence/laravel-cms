<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_attribute_set_id');
            $table->unsignedTinyInteger('type')->default(\App\Models\Utils\OptionTool::$TYPE_TEXT);
            $table->string('name',100);

            // 显示的顺序
            $table->unsignedSmallInteger('position')->default(0);

            //表示显示在页面的哪个位置
            $table->unsignedSmallInteger('location')->default(0);
            $table->text('default_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attributes');
    }
}
