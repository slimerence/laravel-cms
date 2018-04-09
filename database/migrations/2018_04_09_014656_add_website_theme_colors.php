<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWebsiteThemeColors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configurations', function (Blueprint $table) {
            $table->string('menu_bar_color')->nullable();         // 可以按名称检索
            $table->string('menu_bar_color_hover')->nullable();         // 可以按名称检索
            $table->string('theme_main_color')->nullable();         // 可以按名称检索
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
