<div class="card-header" role="tab" id="{{ \App\Models\Utils\PaymentTool::$METHOD_ID_WECHAT }}">
    <a data-toggle="collapse" href="#pm-we-chat-c"
       aria-expanded="true" aria-controls="pm-we-chat-c" class="pm-select-trigger">
        <h5>
             {{ trans('payment.Wechat') }}&nbsp;<i class="fab fa-weixin fa-2x we-chat-pay-logo"></i>
        </h5>
        <i class="fas fa-check fa-lg" style="color: white;margin-top: 0;"></i>
    </a>
</div>
<div class="collapse" data-parent="#payment-method-list"></div>