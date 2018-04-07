<div class="my-2 my-lg-0 extra">
    <a class="view-order-btn" href="{{ url('view_cart') }}">
        <i class="fa fa-cart-plus" aria-hidden="true"></i>
        <span class="">Cart </span>(<span id="global-shopping-cart-count">{{ isset($cart) ? $cart->content()->count() : 0 }}</span>)
        @if(isset($cart) && $cart->total()>0)
            <span id="global-shopping-cart-total">&nbsp;{{ config('system.CURRENCY') }} {{ $cart->total() }}</span>
        @else
            <span id="global-shopping-cart-total"></span>
        @endif
    </a>
    @if(isset($cart) && $cart->total()>0)
        <a class="checkout-btn" href="{{ url('/frontend/place_order_checkout') }}">
            <i class="fa fa-credit-card mr-1 {{ $agentObject->isPhone() ? 'd-none d-sm-block' : null }}" aria-hidden="true"></i>&nbsp;Checkout
        </a>
    @endif
</div>