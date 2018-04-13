<nav class="navbar bg-dark-gradient" role="navigation" aria-label="main navigation" id="navigation"  style="z-index: 9999;">
    <div class="navbar-brand">
        <button class="button toggle-button mobile-drawer-trigger">
            <i class="fa fa-bars"></i>
        </button>
        <h1 class="full-width mobile-head-txt">
            <a class="has-text-white has-text-centered" href="{{ url('/') }}">
                {{ str_replace('_',' ',env('APP_NAME','Home')) }}
            </a>
        </h1>
    </div>
</nav>