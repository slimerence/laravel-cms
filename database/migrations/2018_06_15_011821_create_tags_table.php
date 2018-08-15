<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');

            // 目录的排序
            $table->unsignedSmallInteger('position')->default(0);
            $table->uuid('uuid');
            $table->string('name',100);
            $table->string('uri',255);
            $table->text('keywords')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('name_cn',100)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('tags');
    }
}
