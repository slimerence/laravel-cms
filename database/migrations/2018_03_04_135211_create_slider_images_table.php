<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSliderImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider_images', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('position')->default(0);
            $table->unsignedInteger('slider_id');
            $table->unsignedInteger('media_id')->default(0);

            $table->string('html_tag')->nullable();     // 当前Slide所用的tag
            $table->string('classes_name')->nullable(); // 当前Slide所用的css
            $table->text('extra_html')->nullable();     // 当前Slide中包含的html代码
            $table->text('link_to')->nullable();        // 当前slider点击后的url

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slider_images');
    }
}
