<nav id="menu" class="menu slideout-menu slideout-menu-left">
    <section class="menu-section">
        @if(empty($siteConfig->logo))
            <h3 class="menu-section-title">{{ str_replace('_',' ',env('APP_NAME','Home')) }}</h3>
        @else
            <img src="{{ asset($siteConfig->logo_dark ? $siteConfig->logo_dark : $siteConfig->logo) }}" alt="Logo" class="logo-img-mobile">
        @endif
    </section>
    <!-- e-commerce -->
    @if(isset($categoriesTree) && count($categoriesTree) > 0)
        <section class="menu-section mb-10 mt-10">
            <a class="has-text-white navbar-item" href="#">
                Catalog
            </a>
        @foreach($categoriesTree as $category)
            <p class="pl-20">
                <a class="has-text-white navbar-item" href="{{ $category->getCategoryUrl() }}">- {{ $category->name }}</a>
            </p>
        @endforeach
        </section>
    @endif

    @foreach($rootMenus as $key=>$rootMenu)
        <section class="menu-section mb-10 mt-10">
        <?php
        $tag = $rootMenu->html_tag;
        $children = $rootMenu->getSubMenus();
        if($tag && $tag !== 'a'){
            echo '<'.$tag.'>';
        }
        ?>
        @if(count($children) == 0)
            <a class="has-text-white {{ $rootMenu->css_classes }}" href="{{ url($rootMenu->link_to=='/' ? '/' : '/page'.$rootMenu->link_to) }}">
                {{ app()->getLocale()=='cn' && !empty($rootMenu->name_cn) ? $rootMenu->name_cn : $rootMenu->name }}
            </a>
        @else
            <h3 class="menu-section-title">
                <a class="has-text-white" href="{{ url($rootMenu->link_to=='/' ? '/' : '/page'.$rootMenu->link_to) }}">
                    {{ app()->getLocale()=='cn' && !empty($rootMenu->name_cn) ? $rootMenu->name_cn : $rootMenu->name }}
                </a>
            </h3>
            <ul class="menu-section-list">
                @foreach($children as $sub)
                    <li>
                        <a class="has-text-grey-light" href="{{ url($sub->link_to=='/' ? '/' : '/page'.$sub->link_to) }}">
                            {{ app()->getLocale()=='cn' && !empty($sub->name_cn) ? $sub->name_cn : $sub->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
        <?php
        if($tag && $tag !== 'a'){
            echo '</'.$tag.'>';
        }
        ?>
        </section>
    @endforeach

    <section class="menu-section mb-10 mt-10">
        <a class="has-text-white navbar-item" href="{{ url('contact-us') }}">
            {{ trans('general.menu_contact') }}
        </a>
    </section>
</nav>