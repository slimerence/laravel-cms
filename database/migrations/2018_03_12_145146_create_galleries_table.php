<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('short_code');
            $table->string('wrapper_classes')->nullable();
            $table->string('lib')->nullable();  // 使用哪个前端的gallery js库

            // 作为div的attribute的输出
            $table->text('attributes_text')->nullable();

            // Gallery 每行的照片数
            $table->unsignedSmallInteger('images_per_row')->default(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('galleries');
    }
}
