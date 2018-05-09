@extends(_get_frontend_layout_path('catalog'))
@section('content')
    <div class="content pl-20 pr-20 page-content-wrap" id="category-view-manager">
        @if(isset($featureProducts) && count($featureProducts)>0)
        <hr>
        <div class="columns">
            <div class="column is-1">
                <i class="far fa-thumbs-up is-size-1 has-text-danger"></i>
                <br>
                <p class="has-text-left has-text-danger is-size-7 mt-10">Feature Products</p>
            </div>
            <div class="column is-11-desktop">
                <div class="columns">
                    @foreach($featureProducts as $featureProduct)
                    <div class="column">
                        <div class="show-mask-on-hover">
                            <img src="{{ $featureProduct->getProductDefaultImageUrl() }}" alt="{{ $featureProduct->name }}" class="image mb-10" style="height: 201px;">
                            <div class="mask">
                                <a href="{{ url('catalog/product/'.$featureProduct->uri) }}">
                                    <p class="name is-size-4">{{ $featureProduct->name }}</p>
                                    <p class="price is-size-5">${{ $featureProduct->getFinalPriceGst() }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <hr>
        @endif
        <div class="box is-radiusless mt-20">
            <div class="columns">
                <div class="column">
                    <div class="field is-grouped is-grouped-multiline">
                        <div class="control">
                            <div class="tags">
                                <h1 class="is-size-6 tag is-link">
                                    {{ $category->name }}&nbsp;&gt;
                                </h1>
                            </div>
                        </div>
                        @foreach($category->children as $subCategory)
                            <?php
                            $count = $subCategory->productsCount();
                            if($count>0){
                            ?>
                            <div class="control">
                                <div class="tags has-addons">
                                    <h2 class="is-size-6 tag is-white">
                                        <a href="{{ url('category/view/'.$subCategory->uri) }}">{{ $subCategory->name }}&nbsp;({{ $subCategory->productsCount() }})</a>
                                    </h2>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="columns border-dotted-top">
                <div class="column is-2">
                    <p class="is-size-6">
                        <a class="has-text-grey" href="{{ url('catalog/brands/all') }}">&nbsp;Brands</a>
                    </p>
                </div>
                <div class="column">
                    <?php $brands = $category->loadBrands(); ?>
                    <div class="control">
                        <div class="tags has-addons">
                            @foreach($brands as $brand)
                            <a class="tag is-size-7 mr-10" href="{{ url('/catalog/brand/load?name='.$brand->name) }}">{{ $brand->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($price_ranges) && count($price_ranges)>0)
            <div class="columns border-dotted-top">
                <div class="column is-2">
                    <p class="is-size-6">
                        <a class="has-text-grey" href="{{ url('catalog/brands/all') }}">&nbsp;Price Range</a>
                    </p>
                </div>
                <div class="column">
                    <div class="control">
                        <div class="tags has-addons">
                            @foreach($price_ranges as $key=>$priceNumber)
                                @if($key < count($price_ranges) - 1)
                                <a class="tag is-size-7 mr-10" href="{{ url('category/view/'.$category->uri.'?orderBy=price&fr='.$priceNumber.'&to='.$price_ranges[$key+1]) }}">
                                    ${{ number_format($priceNumber) }} - ${{ number_format($price_ranges[$key+1]) }}
                                </a>
                                @endif
                            @endforeach
                            @if(count($price_ranges)==1)
                                <a class="tag is-link is-size-7 mr-10" href="{{ url('category/view/'.$category->uri) }}">
                                    Reset Price Range
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="columns">
            @if(isset($promotionProducts) && count($promotionProducts)>0)
            <div class="column is-2">
                <div class="content">
                    <p class="has-text-left has-text-danger is-size-4 is-marginless mb-10">Promotion</p><br>
                    @foreach($promotionProducts as $promotionProduct)
                    <a href="{{ url('catalog/product/'.$promotionProduct->uri) }}">
                        <img src="{{ $promotionProduct->getProductDefaultImageUrl() }}" alt="{{ $promotionProduct->name }}" class="image mb-10">
                        <p class="is-size-6 has-text-grey mb-10">{{ $promotionProduct->name }}</p>
                        <div class="price-box">
                            <p class="is-pulled-left {{ $promotionProduct->special_price ? 'has-text-grey-lighter' : 'has-text-danger' }} is-size-5">${{ $promotionProduct->getDefaultPriceGST() }}</p>
                            @if($promotionProduct->special_price)
                                <p class="is-pulled-right has-text-danger is-size-4">${{ $promotionProduct->getSpecialPriceGST() }}</p>
                            @endif
                        </div>
                    </a>
                    <div class="is-clearfix"></div>
                    <hr>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="column category-products-page-wrap">
                @include(_get_frontend_theme_path('catalog.elements.filters'))
                @include(_get_frontend_theme_path('catalog.elements.simple_paginate'))
                <div class="is-clearfix"></div>
                <br>
                <?php
                    $productsChunk = $products->chunk(4);
                    // 尝试加载产品的 Brand 的 Logo, 为了减少数据库的查询, 在这里做一个缓存
                    $imageLogoBuffer = [];

                foreach ($productsChunk as $row) {
                    ?>
                    <div class="columns is-multiline">
                        @foreach($row as $key=>$product)
                        <div class="column is-3-desktop is-12-mobile category-product-wrap">
                            <div class="content box">
                                <?php
                                    if(!isset($imageLogoBuffer[$product->brand])){
                                        $imageLogoBuffer[$product->brand] = $product->getBrandLogoUrl();
                                    }
                                    if($imageLogoBuffer[$product->brand]){
                                    ?>
                                    <p class="cp-brand-wrap">
                                        <a href="{{ url('catalog/brand/load?name='.$product->brand) }}">
                                            <img src="{{ $imageLogoBuffer[$product->brand] }}" alt="{{ $product->brand }}" class="cp-brand">
                                        </a>
                                    </p>
                                    <?php
                                    }
                                ?>
                                @if($product->group_id)
                                    <p class="is-pulled-right"><span class="tag is-danger">{{ $product->group->name }}</span></p>
                                @else
                                    <p class="is-pulled-right"><span class="tag is-info">{{ str_replace('_',' ',env('APP_NAME')) }}</span></p>
                                @endif
                                <div class="is-clearfix"></div>
                                    <a href="{{ url('catalog/product/'.$product->uri) }}">
                                    <p class="has-text-centered p-img">
                                        <img src="{{ $product->getProductDefaultImageUrl() }}" alt="{{ $product->name }}" class="image">
                                    </p>
                                    <div class="price-box">
                                        <p class="is-pulled-left {{ $product->special_price ? 'has-text-grey-lighter' : 'has-text-danger' }} is-size-5">${{ $product->getDefaultPriceGST() }}</p>
                                        @if($product->special_price)
                                            <p class="is-pulled-right has-text-danger is-size-4">${{ $product->getSpecialPriceGST() }}</p>
                                        @endif
                                    </div>
                                    <div class="is-clearfix"></div>
                                    <p class="is-size-6 has-text-grey mb-10 mh48">{{ $product->name }}</p>
                                </a>
                                @if($product->serial_name)
                                <div class="control is-pulled-left"><div class="tags has-addons">
                                        <a class="tag" href="#">
                                            Serial: {{ $product->serial_name }}
                                        </a>
                                </div></div>
                                @endif
                                <div class="control is-pulled-right">
                                    <div class="tags has-addons">
                                        <a class="tag is-success" href="#" v-on:click.prevent="startEnquiry('{{ $product->name }}','{{ $product->uuid }}')">
                                            <i class="far fa-comment"></i>&nbsp;Send Enquiry
                                        </a>
                                    </div>
                                </div>
                                <div class="is-clearfix"></div>
                            </div>
                        </div>

                        @endforeach
                    </div>
                    <?php
                }
                ?>
                <div class="content">
                    <div class="is-pulled-right">
                        {{ $cps->appends($paginationAppendParams)->links() }}
                    </div>
                </div>
            </div>
        </div>

        <el-dialog title="Enquiry" :visible.sync="showSendEnquiryForm">
            <el-form :model="enquiryForm" :rules="rules" ref="enquiryDataForm">
                <el-form-item label="Product" :label-width="formLabelWidth">
                    <el-input v-model="enquiryForm.selectedProductName" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="Your Name" v-show="!userIsLocated" :label-width="formLabelWidth" prop="name">
                    <el-input v-model="enquiryForm.name" placeholder="Your Name"></el-input>
                </el-form-item>
                <el-form-item label="Email" v-show="!userIsLocated" :label-width="formLabelWidth" prop="email">
                    <el-input v-model="enquiryForm.email" placeholder="Your Email"></el-input>
                </el-form-item>
                <el-form-item label="Phone" :label-width="formLabelWidth" prop="phone">
                    <el-input v-model="enquiryForm.phone" placeholder="Your phone #"></el-input>
                </el-form-item>
                <el-form-item label="Message" :label-width="formLabelWidth">
                    <el-input type="textarea" v-model="enquiryForm.message" placeholder="Say Something ..."></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="cancelEnquiry">Cancel</el-button>
                <el-button type="primary" @click="sendEnquiryAction('enquiryDataForm')">Submit</el-button>
            </div>
        </el-dialog>
    </div>
@endsection