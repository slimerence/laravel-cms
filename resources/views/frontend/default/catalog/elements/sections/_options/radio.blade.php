<?php
/**
 * 当产品的option是单选项 Radio
 */
if($product_option->items){
$uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
?>
<div class="product-option-input-wrap">
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">{{ $product_option->name }}</label>
        <div class="col-sm-9">
            @foreach($product_option->items as $key=>$item)
                <div class="form-check form-check-inline mb-0 mt-1">
                    <label class="form-check-label">
                        <input
                                v-on:click="optionClickedHandler({{ $product_option->id }}, {{ $item->extra_value }})"
                                name="product_option_{{ $product_option->id }}[]"
                                id="product_option_{{ $uuid }}"
                                data-type="product_option"
                                data-value="{{ $product_option->id }}"
                                data-extra-value="{{ $item->extra_value }}"
                                class="form-check-input" type="radio"
                                value="{{ $item->id }}">
                        {{ $item->label }} {{ $item->extra_value > 0 ? '+'.config('system.CURRENCY').number_format($item->extra_value,2) : null }}
                    </label>
                </div>
            @endforeach
                <div class="invalid-feedback" id="invalid-feedback-product_option_{{ $uuid }}"></div>
        </div>
    </div>
</div>
<?php
}
?>
