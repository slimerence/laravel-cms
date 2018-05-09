<?php
$categories = [];
foreach ($categoriesTree as $item) {
    $data = [
        'id' => $item->uuid,
        'uri' => $item->uri,
        'name' => app()->getLocale()=='cn' ? $item->name_cn : $item->name,
    ];
    // 目录必须至少包含一个产品或者一个子目录才可以被加载到导航栏
    $sub = $item->loadForNav();
    if(count($sub['subs'])>0 || count($sub['products']) > 0){
        $data = array_merge($data, $sub);
        $categories[] = $data;
    }
}
?>
<catalog-viewer
    category-loading-url="category/view"
    product-loading-url="catalog/product"
    :first-level-categories="{{ json_encode($categories) }}"
    :width="1280"
    :height="600"
    :left-width="{{ config('system.CATALOG_TRIGGER_MENU_WIDTH') }}"
    :show-now="true"
    categories-list-bg-color="{{ $siteConfig->menu_bar_color }}"
    >
</catalog-viewer>
