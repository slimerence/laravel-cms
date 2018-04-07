<?php
    $mainLocationProductAttributes = [];
    foreach ($product_attributes as $key=>$productAttribute){
        if($productAttribute->location == \App\Models\Utils\OptionTool::$LOCATION_MAIN){
            $mainLocationProductAttributes[] = $productAttribute;
        }
    }
    if(!empty($mainLocationProductAttributes)){
        foreach ($mainLocationProductAttributes as $productAttribute){
            switch ($productAttribute->type){
                case \App\Models\Utils\OptionTool::$TYPE_TEXT:
                    ?>
    @include('frontend.default.catalog.elements.sections._attributes.text')
                    <?php
                    break;
                    case \App\Models\Utils\OptionTool::$TYPE_RICH_TEXT:
                    ?>
    @include('frontend.default.catalog.elements.sections._attributes.rich_text')
                    <?php
                        break;
                default:
                    break;
            }
            echo '<div class="is-clearfix"></div>';
        }
    }
?>
