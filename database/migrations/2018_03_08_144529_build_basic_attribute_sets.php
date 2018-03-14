<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Catalog\Product\ProductAttributeSet;
use App\Models\Catalog\Product\ProductAttribute;

class BuildBasicAttributeSets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ProductAttributeSet::create(
            ['name'=>'General','parent_id'=>0]
        );

        ProductAttribute::create(
            [
                'name'=>'Manufacture',
                'product_attribute_set_id'=>1,
                'type'=>1,
                'position'=>1,
                'location'=>1
            ]
        );

        ProductAttribute::create(
            [
                'name'=>'Material',
                'product_attribute_set_id'=>1,
                'type'=>1,
                'position'=>10,
                'location'=>1
            ]
        );
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
