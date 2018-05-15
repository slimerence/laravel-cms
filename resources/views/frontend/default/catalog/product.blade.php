@extends(_get_frontend_layout_path('catalog'))
@section('content')
    <div class="content pl-20 pr-20 page-content-wrap" id="product-view-manager-app">
        <hr>
        <div class="columns">
            <div class="column is-6">
                <div class="fotorama" data-allowfullscreen="true" data-nav="thumbs" data-navposition="left" data-arrows="false" data-autoplay="true" data-height="350" data-maxheight="100%">
                    @foreach($product_images as $key=>$media)
                        <img src="{{ asset($media->url) }}">
                    @endforeach
                </div>
                @if($product->is_group_product)
                    @include(_get_frontend_theme_path('catalog.elements.sections.grouped_products'))
                @endif
            </div>
            <div class="column is-6 product-info-wrap">
                <h2>
                    {{ $product->name }}&nbsp;
                    @if($product->manage_stock && $product->stock<$product->min_quantity)
                        <span class="badge badge-pill badge-danger">Out of Stock</span>
                    @endif
                </h2>
                <p class="sku-txt">SKU: {{ $product->sku }}</p>

                @include(_get_frontend_theme_path('catalog.elements.sections.short_description'))

                <div class="main-attributes content">
                    @include(_get_frontend_theme_path('catalog.elements.sections.attributes_main'))
                </div>

                @include(_get_frontend_theme_path('catalog.elements.sections.price'))

                <form id="add-to-cart-form">
                    {{ csrf_field() }}
                    <input type="hidden" name="product_id" value="{{ $product->uuid }}">
                    <input type="hidden" name="user_id" value="{{ session('user_data.uuid') }}">

                    @if(count($product_colours)>0)
                        <div class="options-wrap">
                            @include(_get_frontend_theme_path('catalog.elements.sections._options.colour'))
                        </div>
                    @endif

                    @if(count($product_options)>0)
                        <div class="options-wrap">
                            @include(_get_frontend_theme_path('catalog.elements.sections.options'))
                        </div>
                    @endif

                    <div class="add-to-cart-form-wrap">
                        <div class="field mb-20">
                            <label class="label">
                                Quantity
                                @if(!empty($product->unit_text))
                                    <span class="has-text-danger is-size-7">(Unit: {{ $product->unit_text }})</span>
                                @endif
                            </label>
                            <div class="control quantity-input-wrap">
                                <input
                                        data-name="quantity"
                                        name="quantity"
                                        type="number"
                                        class="input quantity-input"
                                        placeholder="Quantity"
                                        value="{{ $product->min_quantity }}"
                                        min="{{ $product->min_quantity }}"
                                >
                            </div>
                            <small id="emailHelp" class="form-text text-muted">
                                Notice: Minimum quantity is <strong>{{ $product->min_quantity }}{{ !empty($product->unit_text)?' '.$product->unit_text:null }}</strong> per order.
                            </small>
                        </div>
                        @if(!$product->manage_stock)
                            <button v-on:click="addToCartAction($event)" id="add-to-cart-btn" type="submit" class="button is-danger">
                                <i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;Add to Cart
                            </button>
                            <a href="{{ url('/frontend/place_order_checkout') }}" id="shortcut-checkout-btn" class="button is-link shortcut-checkout-btn is-invisible">
                                <i class="fa fa-credit-card" aria-hidden="true"></i>&nbsp;Checkout Now!
                            </a>
                        @else
                            @if($product->stock<$product->min_quantity)
                                <button id="send-enquiry-for-shopping-btn" type="submit" class="button">Send Enquiry</button>
                            @else
                                <button id="add-to-cart-btn" type="submit" class="button add-to-cart-btn">Add to Cart</button>
                            @endif
                        @endif
                    </div>
                </form>
            </div>
        </div>
        @include(_get_frontend_theme_path('catalog.elements.sections.description'))
    </div>
    <?php
        $relatedProducts = $product->loadRelatedProducts();
    ?>
    @if(count($relatedProducts))
    <div class="content pl-20 pr-20 page-content-wrap">
        <hr>
        <h3 class="has-text-centered">Similar Products</h3>
        <div class="columns is-multiline">
        @foreach($relatedProducts as $rp)
            <div class="column is-3">
                <div class="content box">
                    <div class="is-clearfix"></div>
                    <a href="{{ url('catalog/product/'.$rp->uri) }}">
                        <p class="has-text-centered p-img">
                            <img src="{{ $rp->getProductDefaultImageUrl() }}" alt="{{ $rp->name }}" class="image">
                        </p>
                        <div class="price-box">
                            <p class="is-pulled-left {{ $rp->special_price ? 'has-text-grey-lighter' : 'has-text-danger' }} is-size-5">${{ $rp->getDefaultPriceGST() }}</p>
                            @if($rp->special_price)
                                <p class="is-pulled-right has-text-danger is-size-4">${{ $rp->getSpecialPriceGST() }}</p>
                            @endif
                        </div>
                        <div class="is-clearfix"></div>
                        <p class="is-size-6 has-text-grey mb-10 mh48">{{ $rp->name }}</p>
                    </a>
                    @if($rp->serial_name)
                        <div class="control is-pulled-left"><div class="tags has-addons">
                                <a class="tag" href="#">
                                    Serial: {{ $rp->serial_name }}
                                </a>
                            </div></div>
                    @endif
                    <div class="is-clearfix"></div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
    @endif
@endsection