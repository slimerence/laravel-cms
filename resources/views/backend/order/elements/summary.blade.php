<div class="content-block content-half">
    <div class="content-title-line">
        <h3>Summary</h3>
    </div>
    <div class="content-detail-wrap">
        <div class="content-line">
            <label>Date: </label>
            <label class="value">{{ substr($order->created_at,0, 11) }}</label>
        </div>
        <div class="content-line">
            <label>Total: </label>
            <label class="value">{{ config('system.CURRENCY') }} {{ number_format($order->total,2) }}</label>
        </div>
        <div class="content-line">
            <label>Place Order: </label>
            <label class="value"><b class="text-primary">{{ $order->place_order_number }}</b></label>
        </div>
        <div class="content-line">
            <label>Group: </label>
            <label class="value">{{ \App\Models\Utils\UserGroup::RoleName($order->customer->role) }}</label>
        </div>
    </div>
</div>