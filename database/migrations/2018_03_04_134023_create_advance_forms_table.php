<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvanceFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_forms', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('short_code');
            $table->string('wrapper_classes')->nullable();

            // 作为div的attribute的输出
            $table->text('attributes_text')->nullable();

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
        Schema::dropIfExists('advance_forms');
    }
}
