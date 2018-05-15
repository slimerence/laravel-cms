<!-- 产品详情及其他 -->
<div class="content product-additional-wrap pt-40" id="switchable-tabs-app" style="background: #fcfcfc;">
    <div class="tabs">
        <ul style="padding-left: 0;margin-right: 32px;">
            @if(!empty($product->description))
            <li class="tab-trigger-btn is-marginless is-active">
                <a href="#product-description-tab-content">Description</a>
            </li>
            @endif
            @foreach($product_attributes as $key=>$product_attribute)
                @if($product_attribute->location == \App\Models\Utils\OptionTool::$LOCATION_ADDITIONAL)
            <li class="tab-trigger-btn is-marginless {{ $key==0&&empty($product->description) ? 'is-active' : null }}">
                <a href="#tab-content-{{$key}}">{{ $product_attribute->name }}</a>
            </li>
                @endif
            @endforeach
        </ul>
    </div>
    <div class="is-clearfix"></div>
    <div id="tab-contents" class="content" style="padding: 40px;">
        @if(!empty($product->description))
            <div class="tab-pane" id="product-description-tab-content">
                @if(count($productDescriptionTop) > 0)
                    @foreach($productDescriptionTop as $b)
                        <div class="content">{!! $b->content !!}</div>
                    @endforeach
                    <div class="is-clearfix"></div>
                @endif
                {!! $product->description !!}
                @if(count($productDescriptionBottom) > 0)
                    <div class="is-clearfix"></div>
                    @foreach($productDescriptionBottom as $b)
                        <div class="content">{!! $b->content !!}</div>
                    @endforeach
                @endif
            </div>
        @endif
        @foreach($product_attributes as $key=>$product_attribute)
            @if($product_attribute->location == \App\Models\Utils\OptionTool::$LOCATION_ADDITIONAL)
            <div class="tab-pane {{ $key==0&&empty($product->description) ? '' : 'hidden' }}" id="tab-content-{{$key}}">
                <?php
                $productAttributeValue = $product_attribute->valuesOf($product);
                // {!! $productAttributeValue->value !!}
                if(count($productAttributeValue)>0){
                    echo $productAttributeValue[0]->value;
                }
                ?>
            </div>
            @endif
        @endforeach
    </div>
</div>