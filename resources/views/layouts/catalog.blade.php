<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" ⚡>
@include(_get_frontend_layout_path('frontend.head'))
<body>
@if($agentObject->isPhone())
    <!-- Mobile Version -->
    @include( _get_frontend_layout_path('frontend.mobile.nav') )
    <main id="panel" class="panel-mask">
        <header>
            @include(_get_frontend_layout_path('frontend.mobile.header_mobile'))
        </header>
        <section id="main" class="section is-paddingless">
            <div class="container is-fluid">
                @include(_get_frontend_layout_path('frontend.session_flash_msg_box'))
                <div id="panel">
                    @yield('content')
                </div>
                @include(_get_frontend_layout_path('frontend.footer'))
            </div>
        </section>
    </main>
    @if(env('activate_ecommerce',false))
        @include( _get_frontend_layout_path('frontend.mobile.shopping_cart_mobile') )
    @endif
@else
    <!-- Desktop Version -->
    @include(_get_frontend_layout_path('frontend.top_bar'))
    <section class="section is-paddingless">
        @include( _get_frontend_layout_path('frontend.header_catalog') )
        <div class="container">
            @include( _get_frontend_layout_path('frontend.promotion') )
        </div>
        <div class="container mt-10">
            @include( _get_frontend_layout_path('frontend.session_flash_msg_box'))
            @yield('content')
            @include( _get_frontend_layout_path('frontend.footer') )
        </div>
    </section>
    @include( _get_frontend_layout_path('frontend.floating_box'))
@endif

@include( _get_frontend_layout_path('frontend.js') )
</body>
</html>