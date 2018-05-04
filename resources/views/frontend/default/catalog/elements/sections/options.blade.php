<h5 class="options-title">Options:</h5>
<hr>
<?php
    foreach ($product_options as $key=>$product_option){
        switch ($product_option->type) {
            case \App\Models\Utils\OptionTool::$TYPE_TEXT:
                ?>@include(_get_frontend_theme_path('catalog.elements.sections._options.text'))<?php
                break;
            case \App\Models\Utils\OptionTool::$TYPE_DROP_DOWN:
                ?>@include(_get_frontend_theme_path('catalog.elements.sections._options.drop_down'))<?php
                break;
            case \App\Models\Utils\OptionTool::$TYPE_RADIO_BUTTON:
                ?>@include(_get_frontend_theme_path('catalog.elements.sections._options.radio'))<?php
                break;
            default:
                break;
        }
    }
?>
<hr>
