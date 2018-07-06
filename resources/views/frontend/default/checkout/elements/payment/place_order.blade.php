<div class="card-header" id="{{ \App\Models\Utils\PaymentTool::$METHOD_ID_PLACE_ORDER }}">
    <a data-toggle="collapse" href="#pm-place-order-c"
       aria-expanded="true" aria-controls="pm-place-order-c"
       class="pm-select-trigger">
        <h5>{{ trans('payment.Place_Order') }}</h5>
        <i class="fas fa-check fa-lg" style="color: white;"></i>
    </a>
</div>
<div class="" data-parent="#payment-method-list">
    <div class="card-body">
        <div class="field">
            <input type="text" name="order_number" class="input" placeholder="{{ trans('general.Required') }}: {{ trans('payment.Place_Order_placeholder') }}">
            <small id="emailHelp" class="form-text text-muted">{{ trans('payment.Place_Order_notice') }}</small>
        </div>
    </div>
</div>