<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('layouts.backend.head')
<body>
<section class="section admin-login-wrapper">
    <div class="container">
        @yield('content')
    </div>
</section>
@include('layouts.backend.js')
</body>
</html>