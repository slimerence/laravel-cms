<div class="row">
    <div class="col">
        <div class="payment-method-title">
            <h4>Payment Method</h4>
        </div>
    </div>
</div>
<div id="payment-method-list" role="tablist">
    <div class="card payment-method-item bg-primary" id="pm-place-order">
        @include(_get_frontend_theme_path('checkout.elements.payment.place_order'))
    </div>
    <div class="card payment-method-item" id="pm-wechat">
        @include(_get_frontend_theme_path('checkout.elements.payment.wechat_off_site'))
    </div>
</div>