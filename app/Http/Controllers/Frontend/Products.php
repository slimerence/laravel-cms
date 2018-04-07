<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Catalog\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Product\Colour;

class Products extends Controller
{
    public function view($uri, Request $request){
        $product = Product::GetByUri($uri);
        $this->dataForView['product'] = $product;
        $this->dataForView['relatedProducts'] = $product->relatedProduct;
        $this->dataForView['product_images'] = $product->get_AllImages();

        /**
         * 产品的属性集的值
         */
        $this->dataForView['product_attributes'] = $product->productAttributes();
        $this->dataForView['product_options'] = $product->options();

        $this->dataForView['product_colours'] = Colour::LoadByProduct($product->id, true)->toArray();

        $this->dataForView['vuejs_libs_required'] = ['product_view'];

        return view('frontend.default.catalog.product',$this->dataForView);
    }
}
