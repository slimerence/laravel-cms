<?php
/**
 * 当产品的option是单选项 Radio
 */
if($product_option->items){
$uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
?>
<div class="product-option-input-wrap mb-10">
    <div class="field is-horizontal">
        <div class="field-label">
            <label class="label">{{ $product_option->name }}</label>
        </div>
        <div class="field-body">
            <div class="field is-narrow">
                <div class="control">
                    @foreach($product_option->items as $key=>$item)
                        <label class="radio">
                            <input
                                    v-on:click="optionClickedHandler({{ $product_option->id }}, {{ $item->extra_value }})"
                                    name="product_option_{{ $product_option->id }}[]"
                                    id="product_option_{{ $uuid }}"
                                    data-type="product_option"
                                    data-value="{{ $product_option->id }}"
                                    data-extra-value="{{ $item->extra_value }}"
                                    class="radio" type="radio"
                                    value="{{ $item->id }}">
                            {{ $item->label }} {{ $item->extra_value > 0 ? '+'.config('system.CURRENCY').number_format($item->extra_value,2) : null }}
                        </label>
                    @endforeach
                    <div class="invalid-feedback" id="invalid-feedback-product_option_{{ $uuid }}"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
