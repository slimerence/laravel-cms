<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
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
    <section class="section is-paddingless bg-grey">
        @include('layouts.frontend.header')
        @include('layouts.frontend.homepage_slider')
        <div class="container border-dark-grey">
            @include('layouts.frontend.session_flash_msg_box')
            @yield('content')
            @include('layouts.frontend.footer')
        </div>
    </section>
    @include('layouts.frontend.floating_box')
@endif

@include('layouts.frontend.js')
</body>
</html>