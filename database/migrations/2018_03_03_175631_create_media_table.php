<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');   // 如果是可下载的产品,使用这个作为下载链接
            $table->unsignedTinyInteger('type')->default(\App\Models\Utils\MediaTool::$TYPE_IMAGE);
            $table->unsignedInteger('target_id'); // 关联的产品或者目录
            $table->text('url');
            $table->string('alt',100)->nullable();
            $table->unsignedSmallInteger('width')->default(0);
            $table->unsignedSmallInteger('height')->default(0);
            $table->unsignedSmallInteger('size')->default(0);
            $table->unsignedSmallInteger('duration')->default(0);
            $table->unsignedSmallInteger('for')->default(\App\Models\Utils\MediaTool::$FOR_PRODUCT);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
}
