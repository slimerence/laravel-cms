<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Menu;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->unsignedSmallInteger('position')->default(1);
            $table->unsignedSmallInteger('level')->default(1);
            $table->unsignedInteger('parent_id')->default(0);
            $table->boolean('active')->default(false);

            $table->string('link_to')->nullable();
            $table->string('css_classes')->nullable();
            $table->string('html_tag')->nullable();
            $table->text('extra_html')->nullable();
            $table->string('name_cn')->nullable();

            $table->timestamps();
        });

        Menu::create([
            'name'=>'Home',
            'name_cn'=>'首页',
            'position'=>'1',
            'level'=>'1',
            'parent_id'=>'0',
            'active'=>true,
            'link_to'=>'/',
            'css_classes'=>'navbar-item',
            'html_tag'=>'a',
            'extra_html'=>null
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
