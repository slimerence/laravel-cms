<div class="content-block content-half">
    <div class="content-title-line">
        <h3>
            Delivery
        </h3>
    </div>
    <div class="content-detail-wrap">
        <div class="content-line">
            <label>To: </label>
            <label class="value">{{ $order->customer->name }}</label>
        </div>
        <div class="content-line">
            <label>Address: </label>
            <label class="value">{{ $order->customer->addressText() }}</label>
        </div>
        <div class="content-line">
            <label>Phone: </label>
            <label class="value">{{ $order->customer->phone }}</label>
        </div>
    </div>
</div>