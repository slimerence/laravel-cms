<!DOCTYPE html>
<html amp lang="{{ app()->getLocale() }}">
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
        @if( \Illuminate\Support\Facades\URL::current() == url('/'))
            <!-- 首页 -->
            @if(env('show_ecommerce_sub_categories_at_home',true))
                @include(_get_frontend_layout_path('frontend.header'))
                <div class="container">
                    <div class="columns is-marginless is-paddingless" id="catalog-viewer-app">
                        <div class="column is-2 is-marginless is-paddingless" style="width: {{ env('catalog_trigger_menu_width',161) }}px;">
                            @include(_get_frontend_layout_path('frontend.catalog_viewer'))
                        </div>
                        <div class="column is-marginless is-paddingless" style="overflow: hidden;">
                            @include(_get_frontend_layout_path('frontend.homepage_slider'))
                        </div>
                    </div>
                </div>
            @else
                @include( _get_frontend_layout_path('frontend.header_catalog') )
                @include( _get_frontend_layout_path('frontend.homepage_slider') )
            @endif
        @else
            <!-- 非首页 -->
            @include( _get_frontend_layout_path('frontend.header_catalog') )
        @endif
        <div class="container">
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