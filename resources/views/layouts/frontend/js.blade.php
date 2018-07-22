<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
@if(isset($paymentMethods))
    <script src="https://js.stripe.com/v3/"></script>
@endif
<script src="{{ asset('js/all.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>
@if(isset($vuejs_libs_required))
    @foreach($vuejs_libs_required as $lib)
        @include('frontend.vuejs.'.$lib)
    @endforeach
@endif