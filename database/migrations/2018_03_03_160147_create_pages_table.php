<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Page;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedSmallInteger('type')->default(Page::$TYPE_STATIC_PAGE);
            $table->string('layout')->nullable();
            $table->string('uri',255)->unique();   // 页面的URI

            $table->string('title',255); // 页面的title
            $table->string('title_cn',255)->nullable(); // 中文页面的title
            $table->text('content')->nullable();

            $table->string('feature_image')->nullable(); // 代表的图片
            $table->text('seo_keyword')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('teasing')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Page::Persistent([
            'layout'=>\App\Models\Utils\ContentTool::$LAYOUT_ONE_COLUMN,
            'title'=>'Home',
            'title_cn'=>'首页',
            'uri'=>'/',
            'content'=>'<h1>Home Page</h1>',
            'seo_keyword'=>'Home Page',
            'seo_description'=>'Home Page',
            'feature_image'=>null,
            'type'=>Page::$TYPE_STATIC_PAGE,
            'teasing'=>'Home Page',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
