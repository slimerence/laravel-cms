@extends(_get_frontend_layout_path('catalog'))
@section('content')
    <div class="content pt-40">
        <div class="columns">
        @if(env('support_wholesale',false))
            <div class="column is-1"></div>
            <div class="column">
                @include(_get_frontend_theme_path('customers.elements.customer'))
            </div>
            <div class="column">
                @include(_get_frontend_theme_path('customers.elements.wholesale'))
            </div>
            <div class="column is-1"></div>
        @else
            <div class="column is-3"></div>
            <div class="column">
                @include(_get_frontend_theme_path('customers.elements.customer'))
            </div>
            <div class="column is-3"></div>
        @endif
        </div>
    </div>
@endsection