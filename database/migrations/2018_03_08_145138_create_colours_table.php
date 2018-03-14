<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colours', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');

            $table->unsignedTinyInteger('type')->default(\App\Models\Utils\ColourTool::$TYPE_HEX_CODE);
            $table->string('name',100);
            $table->float('extra_money',8,2)->default(0);

            // 保存该颜色值的字段, 可以是空, 那么就是代表用文字; 非空的时候, 可以保存 hex 值或者图片的路径, 图片只能为一张
            $table->text('value')->nullable();
            $table->text('imageUrl')->nullable();  // 图片的路径
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colours');
    }
}
