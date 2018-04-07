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
    <div class="attribute-wrap">
        <div class="row">
            <label class="col-md">{{ $productAttribute->name }}: </label>
        </div>
        <div class="row rich-txt-content">
            {!! $theFinalValue !!}
        </div>
    </div>
@endif