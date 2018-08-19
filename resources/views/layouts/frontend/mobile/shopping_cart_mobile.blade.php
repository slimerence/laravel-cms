<div id="shopping-cart-mobile" class="full-width">
    <div class="columns is-mobile">
        <div class="column">
            <p class="has-text-centered">
                <a class="view-order-btn has-text-white" href="{{ url('view_cart') }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="">Cart </span>(<span id="global-shopping-cart-count">{{ isset($cart) ? $cart->content()->count() : 0 }}</span>)
                    @if(isset($cart) && $cart->total()>0)
                        <span id="global-shopping-cart-total">&nbsp;{{ config('system.CURRENCY') }} {{ $cart->total() }}</span>
                    @else
                        <span id="global-shopping-cart-total"></span>
                    @endif
                </a>
            </p>
        </div>
        @if(isset($cart) && $cart->total()>0)
        <div class="column bg-theme-alt">
            <p class="has-text-centered">
                <a class="checkout-btn has-text-white" href="{{ url('/frontend/place_order_checkout') }}">
                    <i class="fa fa-credit-card mr-1 {{ $agentObject->isPhone() ? 'd-none d-sm-block' : null }}" aria-hidden="true"></i>&nbsp;Checkout
                </a>
            </p>
        </div>
        @endif
    </div>
</div>