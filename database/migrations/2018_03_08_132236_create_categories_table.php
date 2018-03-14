<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Catalog\Category;
use Ramsey\Uuid\Uuid;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            // 父目录的ID, 默认为0, 表示父目录为根
            $table->unsignedInteger('parent_id')->default(1);

            // 目录的排序
            $table->unsignedSmallInteger('position')->default(0);

            // 该目录是否显示在导航菜单中
            $table->boolean('include_in_menu')->default(false);
            $table->boolean('as_link')->default(false);

            $table->uuid('uuid');
            $table->string('name',100);

            $table->string('uri',255);

            $table->string('image_path')->nullable();
            $table->text('short_description')->nullable();
            $table->text('keywords')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('name_cn',100)->nullable();
            $table->softDeletes();
        });

        Category::Persistent([
            'uuid'=>Uuid::uuid4()->toString(),
            'name'=>'Root Category',
            'uri'=>Uuid::uuid4()->toString(),
            'as_link'=>false,
            'parent_id'=>0,
            'position'=>0
        ]);

        /**
         * 特色推荐产品
         */
        Category::Persistent([
            'uuid'=>Uuid::uuid4()->toString(),
            'name'=>'Feature Products',
            'uri'=>'Feature-products',
            'as_link'=>false,
            'parent_id'=>1,
            'position'=>1
        ]);

        /**
         * 打折商品
         */
        Category::Persistent([
            'uuid'=>Uuid::uuid4()->toString(),
            'name'=>'Promotion',
            'uri'=>'products-promotion',
            'as_link'=>false,
            'parent_id'=>1,
            'position'=>2
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
