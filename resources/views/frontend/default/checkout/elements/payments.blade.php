<div class="row">
    <div class="col">
        <div class="payment-method-title">
            <h4>{{ trans('payment.Title') }}</h4>
            <p class="has-text-grey mt-10">{{ trans('payment.Subtitle') }}</p>
        </div>
    </div>
</div>
<div id="payment-method-list" role="tablist">
    @foreach($paymentMethods as $key=>$paymentMethod)
        <div class="card payment-method-item {{ $key==0 ? 'bg-primary':null }}"
             id="{{ \App\Models\Utils\PaymentTool::GetMethodIdStringByMethodId($paymentMethod->method_id) }}">
            @include(_get_frontend_theme_path('checkout.elements.payment.'.\App\Models\Utils\PaymentTool::GetTemplateNameByMethodId($paymentMethod->method_id)))
        </div>
    @endforeach
</div>