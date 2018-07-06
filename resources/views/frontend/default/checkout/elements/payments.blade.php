<div class="row">
    <div class="col">
        <div class="payment-method-title">
            <h4>{{ trans('payment.Title') }}</h4>
            <p class="has-text-grey mt-10">{{ trans('payment.Subtitle') }}</p>
        </div>
    </div>
</div>
<div id="payment-method-list" role="tablist">
    <?php
    $availableTypes = \App\Models\Utils\PaymentTool::GetAvailablePaymentTypes();
    ?>
    @foreach($availableTypes as $key=>$availableType)
    <div class="card payment-method-item {{ $key==0 ? 'bg-primary':null }}" id="{{ $availableType['tag_id'] }}">
        @include(_get_frontend_theme_path('checkout.elements.payment.'.$availableType['template']))
    </div>
    @endforeach
</div>