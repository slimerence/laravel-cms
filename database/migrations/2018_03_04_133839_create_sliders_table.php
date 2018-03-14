<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('short_code');
            $table->string('wrapper_classes')->nullable();

            // 作为div的attribute的输出
            $table->text('attributes_text')->nullable();

            $table->string('overlay')->nullable();
            $table->unsignedInteger('interval')->default(5000);
            $table->unsignedSmallInteger('images_per_frame')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sliders');
    }
}
