<div class="card-header" role="tab"
     id="{{ \App\Models\Utils\PaymentTool::$METHOD_ID_STRIPE }}">
    <a data-toggle="collapse" href="#pm-stripe-c"
       aria-expanded="true" aria-controls="pm-stripe-c" class="pm-select-trigger">
        <h5 style="width: 70%;">{{ trans('payment.Stripe_express') }}&nbsp;
            <i class="fab fa-cc-amex fa-2x mr-10" style="color: black;"></i>&nbsp;
            <i class="fab fa-cc-visa fa-2x mr-10"></i>&nbsp;
            <i class="fab fa-cc-mastercard fa-2x mr-10" style="color: orangered;"></i>
        </h5>
        <i class="fas fa-check fa-lg" style="color: white;margin-top: 0;"></i>
    </a>
</div>
<div class="collapse" data-parent="#payment-method-list">
<stripe-payment
    order-form-id="payment-form"
    stripe-publishable-key="{{ $paymentMethod->mode==\App\Models\Settings\PaymentMethod::MODE_LIVE ? $paymentMethod->api_token : $paymentMethod->api_token_test }}"
    result-token-input-id="{{ \App\Models\Utils\PaymentTool::STRIPE_TOKEN_INPUT_ID }}"
></stripe-payment>
<input type="hidden" name="{{ \App\Models\Utils\PaymentTool::STRIPE_TOKEN_INPUT_NAME }}" id="{{ \App\Models\Utils\PaymentTool::STRIPE_TOKEN_INPUT_ID }}">
</div>