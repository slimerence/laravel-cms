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
    :show-now="true"
    >
</catalog-viewer>
