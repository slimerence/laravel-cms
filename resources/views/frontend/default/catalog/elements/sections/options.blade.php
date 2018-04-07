<h5 class="options-title">Options:</h5>
<?php
    foreach ($product_options as $key=>$product_option){
        switch ($product_option->type) {
            case \App\Models\Utils\OptionTool::$TYPE_TEXT:
                ?>@include('frontend.'.config('system.FRONTEND_THEME_NAME').'.product.sections._options.text')<?php
                break;
            case \App\Models\Utils\OptionTool::$TYPE_DROP_DOWN:
                ?>@include('frontend.'.config('system.FRONTEND_THEME_NAME').'.product.sections._options.drop_down')<?php
                break;
            case \App\Models\Utils\OptionTool::$TYPE_RADIO_BUTTON:
                ?>@include('frontend.'.config('system.FRONTEND_THEME_NAME').'.product.sections._options.radio')<?php
                break;
            default:
                break;
        }

    }
?>
