@extends('layouts.catalog')
@section('content')
    <div class="content pl-20 pr-20 page-content-wrap" id="product-view-manager-app">
        <hr>

        <div class="columns">
            <div class="column is-4">
                <div class="fotorama" data-allowfullscreen="true" data-nav="thumbs" data-navposition="left" data-arrows="false" data-autoplay="true" data-height="350" data-maxheight="100%">
                    @foreach($product_images as $key=>$media)
                        <img src="{{ asset($media->url) }}">
                    @endforeach
                </div>
            </div>
            <div class="column is-8 product-info-wrap">
                <h2>
                    {{ $product->name }}&nbsp;
                    @if($product->manage_stock && $product->stock<$product->min_quantity)
                        <span class="badge badge-pill badge-danger">Out of Stock</span>
                    @endif
                </h2>
                <p class="sku-txt">SKU: {{ $product->sku }}</p>

                @include('frontend.default.catalog.elements.sections.short_description')

                <div class="main-attributes content">
                    @include('frontend.default.catalog.elements.sections.attributes_main')
                </div>

                @include('frontend.default.catalog.elements.sections.price')

                <form id="add-to-cart-form">
                    {{ csrf_field() }}
                    <input type="hidden" name="product_id" value="{{ $product->uuid }}">
                    <input type="hidden" name="user_id" value="{{ session('user_data.uuid') }}">

                    @if(count($product_colours)>0)
                        <div class="options-wrap">
                            @include('frontend.default.catalog.elements.sections._options.colour')
                        </div>
                    @endif

                    @if(count($product_options)>0)
                        <div class="options-wrap">
                            @include('frontend.default.catalog.elements.sections.options')
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
        @include('frontend.default.catalog.elements.sections.description')
    </div>
@endsection