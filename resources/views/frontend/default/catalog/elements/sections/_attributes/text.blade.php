<?php
    $theFinalValue = null;

    $values = $productAttribute->valuesOf($product);
    if(count($values)>0){
        $value = $values[0];
        $theFinalValue = $value->value;
    }else{
        if(!empty($product->default_value)){
            $theFinalValue = $product->default_value;
        }
    }
?>

@if($theFinalValue)
    <div class="columns m-5 pl-10">
        <div class="column is-2 is-paddingless">{{ $productAttribute->name }}: </div>
        <div class="column is-paddingless">{{ $theFinalValue }}</div>
    </div>
@endif
