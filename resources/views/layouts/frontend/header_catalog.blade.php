<nav class="navbar custom-header container" role="navigation" aria-label="main navigation" id="navigation"  style="z-index: 9999;">
    <div class="navbar-brand">
        <a class="navbar-item logo-head-text" href="{{ url('/') }}">
            @if(empty($siteConfig->logo))
                {{ str_replace('_',' ',env('APP_NAME','Home')) }}
                @else
                {!! \App\Models\Utils\AMP\MediaUtil::Image(asset($siteConfig->logo),'Logo', 80, 80, 'logo-img') !!}
            @endif
        </a>
    </div>
        @if(env('activate_search_bar',false))
        <div class="navbar-end" id="navigation-app">
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
<nav class="navbar container">
    <div id="navMenu" class="navbar-menu dark-theme-nav">
        <div class="navbar-start">
            @if(isset($categoriesTree) && count($categoriesTree) > 0)
                <a id="catalog-viewer-app" class="navbar-item product-category-root" href="#" style="width: 213px; background-color: #000;color: #fff;">
                    <i class="fas fa-cube"></i>&nbsp;&nbsp;Catalog
                    <div class="columns is-marginless is-paddingless" id="" style="position: absolute;left:0;top:52px;">
                        <?php
                        $categories = [];
                        foreach ($categoriesTree as $item) {
                            $data = [
                                    'id' => $item->uuid,
                                    'name' => app()->getLocale()=='cn' ? $item->name_cn : $item->name,
                            ];
                            $data = array_merge($data, $item->loadForNav());
                            $categories[] = $data;
                        }
                        ?>
                        <catalog-viewer
                                category-loading-url="category/view"
                                product-loading-url="product/view"
                                :first-level-categories="{{ json_encode($categories) }}"
                                :width="1280"
                                :height="600"
                                :left-width="213"
                                :show-now="false"
                                trigger-id=".product-category-root"
                                show-by="hover"
                        >
                        </catalog-viewer>
                    </div>
                </a>
            @endif
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
</nav>