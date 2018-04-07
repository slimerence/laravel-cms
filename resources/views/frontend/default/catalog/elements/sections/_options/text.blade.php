<?php
    if($product_option->item){
        $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
    ?>
    <div class="product-option-input-wrap">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">{{ $product_option->item->label }}</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="product_option_{{ $uuid }}"
                       name="product_option_{{ $product_option->id }}"
                       placeholder="{{ $product_option->item->label }}"
                       data-type="product_option" data-value="{{ $product_option->id }}"
                >
                <div class="invalid-feedback" id="invalid-feedback-product_option_{{ $uuid }}"></div>
            </div>
        </div>
    </div>
    <?php
    }
?>
