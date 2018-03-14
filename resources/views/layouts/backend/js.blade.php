<script defer src="https://use.fontawesome.com/releases/v5.0.0/js/all.js"></script>
<script src="{{ asset('js/backend.js') }}"></script>
@if(isset($vuejs_libs_required))
    @foreach($vuejs_libs_required as $lib)
        @include('backend.vuejs.'.$lib)
    @endforeach
@endif