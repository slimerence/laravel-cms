<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('layouts.backend.head')
<body>
@include('layouts.backend.nav')
<main id="panel" class="panel-mask">
    <header>
        @include('layouts.backend.header')
    </header>
    <section id="main" class="section is-paddingless">
        <div class="container">
            @include('layouts.backend.session_flash_msg_box')
            <div id="panel">
                @yield('content')
            </div>
        </div>
        @include('layouts.backend.footer')
    </section>
</main>
@include('layouts.backend.js')
</body>
</html>