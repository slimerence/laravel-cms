@extends(_get_frontend_layout_path('catalog'))
@section('content')
    <div class="content pt-40" id="place-order-checkout-app" xmlns="http://www.w3.org/1999/html">
        <div class="columns">
            <div class="column">
                <div class="box" style="width: 80%;margin: 0 auto;">
                    <div class="card-body">
                        <h4 class="is-size-4 has-text-link">Order Summary:</h4>
                        <hr class="is-marginless">
                        <p class="columns is-marginless">
                            <span class="column">Customer Name:</span>
                            <span class="column"><b>{{ session('user_data.name') }}</b></span>
                        </p>
                        <p class="columns is-marginless">
                            <span class="column">Cart Subtotal (GST Incl.):</span>
                            <span class="column"><b>{{ config('system.CURRENCY').' '.($cart->total()) }} ({{$cart->count()}} {{ $cart->count()>1 ?'Items':'Item' }})</b></span>
                        </p>
                        <p class="columns is-marginless">
                            <span class="column">Shipping Fee (GST Incl.):</span>
                            <span class="column"><b>{{ config('system.CURRENCY').' '.(number_format($delivery_charge,2)) }}</b></span>
                        </p>
                        <p class="columns is-marginless">
                            <span class="column">Total (GST Incl.):</span>
                            <span class="column"><b>{{ config('system.CURRENCY').' '.(number_format($cart->total()+$delivery_charge,2)) }}</b></span>
                        </p>
                        <a href="{{ url('/view_cart') }}" class="button is-info is-pulled-right"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;Details</a>
                        <div class="is-clearfix"></div>
                    </div>
                </div>

                <div class="box mt-10" style="width: 80%;margin: 0 auto;">
                    <div class="card-body">
                        <h4 class="is-size-4 has-text-link">Delivery Info: </h4>
                        <hr class="is-marginless">
                        <address>
                            <strong>{{ session('hotel_data.name') }}</strong><br>
                            {{ $user->address }}<br>
                            {{ $user->city }}, {{ $user->state }} {{ $user->postcode }}, {{ $user->country }}<br>
                            <span class="has-text-link">Phone: {{ $user->phone }}</span><br>
                        </address>
                        <a href="{{ url('frontend/my_profile/'.session('user_data.uuid')) }}" class="button is-info is-pulled-right"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update</a>
                        <div class="is-clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="column border-box">
                <form method="post" action="{{ url('/frontend/place_order_checkout') }}" id="payment-form">
                    {{ csrf_field() }}
                    <input type="hidden" name="payment_method" value="pm-place-order" id="payment-method-input">
                    @include(_get_frontend_theme_path('checkout.elements.payments'))

                    <div class="order-notes-wrap">
                        <div class="field">
                            <label class="label">My Notes</label>
                            <textarea class="textarea" name="notes" placeholder="Please leave your notes here ..." rows="3"></textarea>
                        </div>
                    </div>

                    <div class="field mt-10">
                        <label class="label">
                            <input required type="checkbox" name="agree" class="checkbox" checked>
                            I agree to
                            <a target="_blank" class="hyperlink" href="{{ url('/frontend/content/view/terms') }}">Terms and Conditions</a> and <a target="_blank" class="hyperlink" href="{{ url('/frontend/content/view/privacy-policy') }}">Privacy Policy</a>
                        </label>
                    </div>

                    <button type="submit" class="button is-danger is-pulled-right">Submit Order Now</button>
                </form>
            </div>
        </div>
    </div>
@endsection