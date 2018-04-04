<nav class="navbar custom-header" role="navigation" aria-label="main navigation" id="navigation"  style="z-index: 9999;">
    <div class="navbar-brand">
        <button class="button toggle-button mobile-drawer-trigger" id="toggle-drawer-btn">
            <i class="fa fa-bars"></i>
        </button>
    </div>
    <div id="navMenu" class="navbar-menu" style="border-bottom: solid 1px #ccc;">
        <div class="navbar-start">
            <a class="navbar-item {{ $menuName=='config' ? 'is-active' : null }}" href="{{ url('/home') }}">
                <i class="fas fa-cogs"></i>&nbsp;{{ trans('admin.menu.setting') }}
            </a>
            <a class="navbar-item {{ $menuName=='menus' ? 'is-active' : null }}" href="{{ url('/backend/menus/index') }}">
                <i class="fas fa-compass"></i>&nbsp;{{ trans('admin.menu.menus') }}
            </a>
            <a class="navbar-item {{ $menuName=='pages' ? 'is-active' : null }}" href="{{ url('/backend/pages/index') }}">
                <i class="fas fa-paperclip"></i>&nbsp;{{ trans('admin.menu.pages') }}
            </a>
            <a class="navbar-item {{ $menuName=='blog' ? 'is-active' : null }}" href="{{ url('/backend/blog/index') }}">
                <i class="fab fa-blogger"></i>&nbsp;{{ trans('admin.menu.blog') }}
            </a>
            <a class="navbar-item {{ $menuName=='news' ? 'is-active' : null }}" href="{{ url('/backend/news/index') }}">
                <i class="far fa-newspaper"></i>&nbsp;{{ trans('admin.menu.news') }}
            </a>
            @if(env('activate_ecommerce',false))
                <a class="navbar-item {{ $menuName=='categories' ? 'is-active' : null }}" href="{{ url('/backend/categories') }}">
                    <i class="fab fa-bitbucket"></i>&nbsp;{{ trans('admin.menu.categories') }}
                </a>
                <a class="navbar-item {{ $menuName=='brands' ? 'is-active' : null }}" href="{{ url('/backend/brands') }}">
                    <i class="fab fa-adversal"></i>&nbsp;{{ trans('admin.menu.brands') }}
                </a>
                <a class="navbar-item {{ $menuName=='catalog' ? 'is-active' : null }}" href="{{ url('/backend/products') }}">
                    <i class="fab fa-product-hunt"></i>&nbsp;{{ trans('admin.menu.products') }}
                </a>
                <a class="navbar-item {{ $menuName=='attribute_sets' ? 'is-active' : null }}" href="{{ url('/backend/attribute-sets') }}">
                    <i class="fas fa-book"></i>&nbsp;{{ trans('admin.menu.attribute_sets') }}
                </a>
                <a class="navbar-item {{ $menuName=='orders' ? 'is-active' : null }}" href="{{ url('/backend/orders') }}">
                    <i class="fas fa-chart-line"></i>&nbsp;{{ trans('admin.menu.orders') }}
                </a>
            @endif
        </div>
    </div>
</nav>