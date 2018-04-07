<?php
$categories = [];
foreach ($categoriesTree as $item) {
    $data = [
        'id' => $item->uuid,
        'uri' => $item->uri,
        'name' => app()->getLocale()=='cn' ? $item->name_cn : $item->name,
    ];
    $data = array_merge($data, $item->loadForNav());
    $categories[] = $data;
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
    >
</catalog-viewer>
