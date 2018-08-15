<nav id="menu" class="menu slideout-menu slideout-menu-left">
    <section class="menu-section">
        <h3 class="menu-section-title">Widgets</h3>
        <ul class="menu-section-list">
            <li><a class="{{ $menuName=='blocks' ? 'is-active' : null }}" href="{{ url('/backend/widgets/blocks') }}"><i class="fas fa-columns"></i>Blocks</a></li>
            <li><a class="{{ $menuName=='sliders' ? 'is-active' : null }}" href="{{ url('/backend/widgets/sliders') }}"><i class="fab fa-slideshare"></i>Sliders</a></li>
            <li><a class="{{ $menuName=='galleries' ? 'is-active' : null }}" href="{{ url('/backend/widgets/galleries') }}"><i class="fas fa-film"></i>Galleries</a></li>
            <li><a class="{{ $menuName=='leads' ? 'is-active' : null }}" href="{{ url('/backend/leads') }}"><i class="fab fa-wpforms"></i>Leads</a></li>
        </ul>
    </section>

    <section class="menu-section">
        <h3 class="menu-section-title">Extensions</h3>
        <ul class="menu-section-list">
            @if(env('activate_wechat', false))
                <li>
                    <a class="{{ $menuName=='blocks' ? 'is-active' : null }}" href="{{ url('/backend/widgets/blocks') }}">
                        <i class="fab fa-weixin"></i>{{ trans('general.menu_wechat') }}
                    </a>
                </li>
            @endif
            @if(env('support_multiple_groups', false))
                <li>
                    <a class="{{ $menuName=='groups' ? 'is-active' : null }}" href="{{ url('/backend/groups') }}">
                        <i class="fas fa-shopping-basket"></i>{{ trans('general.menu_agent') }}
                    </a>
                </li>
            @endif
            @if(env('activate_shipment_manager', false))
                <li>
                    <a class="{{ $menuName=='shipment' ? 'is-active' : null }}" href="{{ url('/backend/shipment') }}">
                        <i class="fas fa-truck"></i>{{ trans('general.menu_shipment') }}
                    </a>
                </li>
            @endif
            @if(env('activate_shipment_manager', false))
                <li>
                    <a class="{{ $menuName=='payment-methods' ? 'is-active' : null }}" href="{{ url('/backend/payment-methods') }}">
                        <i class="fas fa-credit-card"></i>{{ trans('general.menu_payment_methods') }}
                    </a>
                </li>
            @endif
        </ul>
    </section>

    <section class="menu-section">
        <h3 class="menu-section-title">Users</h3>
        <ul class="menu-section-list">
            @if(env('activate_ecommerce', false))
            <li>
                <a class="{{ $menuName=='customers' ? 'is-active' : null }}" href="{{ url('/backend/customers') }}">
                    <i class="fa fa-user"></i>Customers
                </a>
            </li>
            <li>
                <a class="{{ $menuName=='users' ? 'is-active' : null }}" href="{{ url('/backend/system-users') }}">
                    <i class="far fa-address-card"></i>System Accounts
                </a>
            </li>
            @endif
            <li>
                <a class="{{ $menuName=='update-password' ? 'is-active' : null }}" href="{{ url('/backend/update-password') }}">
                    <i class="fa fa-key"></i>Update My Password
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out-alt"></i>Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
            <li>
                <a href="{{ url('/show-user-guide') }}" target="_blank">
                    <i class="fa fa-book"></i>User Guide
                </a>
            </li>
        </ul>
    </section>
</nav>