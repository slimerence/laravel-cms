<nav class="navbar custom-header container border-dark-grey" role="navigation" aria-label="main navigation" id="navigation"  style="z-index: 9999;">
    <div class="navbar-brand">
        <a class="navbar-item logo-head-text" href="{{ url('/') }}">
            @if(empty($siteConfig->logo))
                {{ str_replace('_',' ',env('APP_NAME','Home')) }}
                @else
                <img src="{{ asset($siteConfig->logo) }}" alt="Logo" class="logo-img">
            @endif
        </a>
    </div>
    <div id="navMenu" class="navbar-menu dark-theme-nav">
        <div class="navbar-start">
            @foreach($rootMenus as $key=>$rootMenu)
                <?php
                    $tag = $rootMenu->html_tag;
                    $children = $rootMenu->getSubMenus();
                    if($tag && $tag !== 'a'){
                        echo '<'.$tag.'>';
                    }
                    ?>
                    @if(count($children) == 0)
                        <a class="{{ $rootMenu->css_classes }}" href="{{ url($rootMenu->link_to=='/' ? '/' : '/page'.$rootMenu->link_to) }}">
                            {{ app()->getLocale()=='cn' && !empty($rootMenu->name_cn) ? $rootMenu->name_cn : $rootMenu->name }}
                        </a>
                    @else
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link" href="{{ $rootMenu->link_to == '#' ? '#' : url($rootMenu->link_to) }}">
                                {{ app()->getLocale()=='cn' && !empty($rootMenu->name_cn) ? $rootMenu->name_cn : $rootMenu->name }}
                            </a>
                            <div class="navbar-dropdown is-boxed">
                                @foreach($children as $sub)
                                <a class="navbar-item" href="{{ url('/page'.$sub->link_to) }}">
                                    {{ app()->getLocale()=='cn' && !empty($sub->name_cn) ? $sub->name_cn : $sub->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <?php
                    if($tag && $tag !== 'a'){
                        echo '</'.$tag.'>';
                    }
                ?>
            @endforeach
                <a class="navbar-item" href="{{ url('contact-us') }}">
                    {{ trans('general.menu_contact') }}
                </a>
        </div>
        @if(env('activate_search_bar',false))
        <div class="navbar-end">
            <el-autocomplete
                class="nav-search-form must-on-layer-top"
                v-model="searchKeyword"
                :fetch-suggestions="querySearchAsync"
                placeholder="Search ..."
                @select="handleSelect"
                :trigger-on-focus="false"
                prefix-icon="el-icon-search"
            ></el-autocomplete>
        </div>
        @endif
    </div>
</nav>