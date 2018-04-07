<div class="content-block content-half">
    <div class="content-title-line">
        <h3>Customer</h3>
    </div>
    <div class="content-detail-wrap">
        <div class="content-line">
            <label>Name: </label>
            <label class="value">{{ $order->customer->name }}</label>
        </div>
        <div class="content-line">
            <label>Email: </label>
            <label class="value">
                <a href="mailto:{{ $order->customer->email }}?subject=About order #{{ $order->serial_number }}">
                    {{ $order->customer->email }}
                </a>
            </label>
        </div>
        <div class="content-line">
            <label>Phone: </label>
            <label class="value">{{ $order->customer->phone }}</label>
        </div>
        <div class="content-line">
            <label>Addr: </label>
            <label class="value">{{ $order->customer->addressText() }}</label>
        </div>
    </div>
</div>