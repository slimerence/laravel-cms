<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include(_get_frontend_layout_path('frontend.head'))
<body>
@if($agentObject->isPhone())
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
        @if( \Illuminate\Support\Facades\URL::current() == url('/'))
            <!-- 首页 -->
            @if(env('show_ecommerce_sub_categories_at_home',true))
                @include(_get_frontend_layout_path('frontend.header'))
                {!! div_container() !!}
                    <div class="columns is-marginless is-paddingless" id="catalog-viewer-app">
                        <div class="column is-2 is-marginless is-paddingless" style="width: {{ env('catalog_trigger_menu_width',161) }}px;">
                            @include(_get_frontend_layout_path('frontend.catalog_viewer'))
                        </div>
                        <div class="column is-marginless is-paddingless" style="overflow: hidden;">
                            @include(_get_frontend_layout_path('frontend.homepage_slider'))
                        </div>
                    </div>
                {!! div_end() !!}
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