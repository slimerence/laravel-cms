<?php
    if($product_option->item){
        $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
    ?>
    <div class="product-option-input-wrap mb-10">
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label">{{ $product_option->item->label }}</label>
            </div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <input type="text" class="input" id="product_option_{{ $uuid }}"
                               name="product_option_{{ $product_option->id }}"
                               placeholder="{{ $product_option->item->label }}"
                               data-type="product_option" data-value="{{ $product_option->id }}"
                        >
                    </div>
                    <div class="invalid-feedback help is-danger" id="invalid-feedback-product_option_{{ $uuid }}"></div>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
?>
