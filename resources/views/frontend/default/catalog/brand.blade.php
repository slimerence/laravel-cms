@extends(_get_frontend_layout_path('catalog'))
@section('content')
    <div class="content pl-20 pr-20 page-content-wrap">
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
                                <a href="{{ url('catalog/product/'.$featureProduct->uri) }}">
                                    <img src="{{ $featureProduct->getProductDefaultImageUrl() }}" alt="{{ $featureProduct->name }}" class="image mb-10">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <hr>
        @endif

        <div class="box is-radiusless">
            <div class="columns">
                <div class="column">
                    <figure class="image" style="width: 200px;">
                        <img src="{{ $brand->getImageUrl() }}" alt="{{ $brand->name }}">
                    </figure>
                </div>
                @if($brand->extra_html)
                    <div class="column is-8">
                    {!! $brand->extra_html !!}
                    </div>
                @endif
            </div>

            @if(isset($price_ranges) && $price_ranges)
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
                                        <a class="tag is-size-7 mr-10" href="{{ url('catalog/brand/load?name='.$brand->name.'&fr='.$priceNumber.'&to='.$price_ranges[$key+1]) }}">
                                            ${{ $priceNumber }} - ${{ $price_ranges[$key+1] }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="columns">
            <div class="column">
                @include(_get_frontend_theme_path('catalog.elements.filters_brand'))
                @include(_get_frontend_theme_path('catalog.elements.simple_paginate_brand'))
                <div class="is-clearfix"></div>
                <br>
                <?php
                $productsChunk = $products->chunk(4);
                foreach ($productsChunk as $row) {
                ?>
                <div class="columns">
                    @foreach($row as $key=>$product)
                        <div class="column is-3-desktop is-12-mobile">
                            <div class="content box">
                                <p class="is-pulled-left has-text-left">Brand: <a href="{{ url('catalog/load-brand?name='.$product->brand) }}">{{ $product->brand }}</a></p>
                                @if($product->group_id)
                                    <p class="is-pulled-right"><span class="tag is-danger">{{ $product->group->name }}</span></p>
                                @else
                                    <p class="is-pulled-right"><span class="tag is-info">ICKE</span></p>
                                @endif
                                <div class="is-clearfix"></div>
                                <a href="{{ url('catalog/product/'.$product->uri) }}">
                                    <p>
                                        <img src="{{ $product->getProductDefaultImageUrl() }}" alt="{{ $product->name }}" class="image">
                                    </p>
                                    <div class="price-box">
                                        <p class="is-pulled-left {{ $product->special_price ? 'has-text-grey-lighter' : 'has-text-danger' }} is-size-5">AUD${{ $product->default_price }}</p>
                                        @if($product->special_price)
                                            <p class="is-pulled-right has-text-danger is-size-4">AUD${{ $product->special_price }}</p>
                                        @endif
                                    </div>
                                    <div class="is-clearfix"></div>
                                    <p class="is-size-6 has-text-grey mb-10">{{ $product->name }}</p>
                                </a>

                                <div class="control is-pulled-right">
                                    <div class="tags has-addons">
                                        <a class="tag" href="#variables"><i class="far fa-comment"></i>&nbsp;Send Enquiry</a>
                                        <a class="tag is-success" href="#variables"><i class="fas fa-cart-arrow-down"></i>&nbsp;Add to Cart</a>
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
                    @include(_get_frontend_theme_path('catalog.elements.simple_paginate_brand'))
                </div>
            </div>
        </div>
    </div>
@endsection