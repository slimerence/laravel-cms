<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" ⚡>
@include('layouts.frontend.head')
<body>
@if($agentObject->isPhone())
    <!-- Mobile Version -->
    @include('layouts.frontend.mobile.nav')
    <main id="panel">
        <header>
            @include('layouts.frontend.mobile.header_mobile')
        </header>
        <section id="main" class="section">
            <div class="container is-fluid">
                @include('layouts.frontend.session_flash_msg_box')
                <div id="panel">
                    @yield('content')
                </div>
                @include('layouts.frontend.footer')
            </div>
        </section>
    </main>
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