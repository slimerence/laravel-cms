<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
@if(env('payment_stripe', false))
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
@endif
<script src="{{ asset('js/all.js') }}"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>
@if(isset($vuejs_libs_required))
    @foreach($vuejs_libs_required as $lib)
        @include('frontend.vuejs.'.$lib)
    @endforeach
@endif