<?php
foreach ($sliders as $slider) {

    $sliderImages = $slider->getSliderImages();
    // 为了保持美观,所有的照片的尺寸,都要和第一张的一样,因此需要强制调整一下
    $ratio = null;
    $width = '100%';
    $height = 'auto';
    if(count($sliderImages) > 0){
        foreach ($sliderImages as $image) {
            if($slider->images_per_frame>1){
                $width = (string)$image->width;
                $height = (string)$image->height;
                $ratio = 'is-'.substr($width,0,1).'by'.substr($height,0,1);
            }
            break;
        }
    }
?>
@if($agentObject->isPhone())
    <div class="{{ $slider->wrapper_classes }}" {{ $slider->attributes_text }}>
        <div class="carousel carousel-animated carousel-animate-slide" data-autoplay="true" data-delay="{{ $slider->interval }}">
            <div class='carousel-container'>
                @foreach($sliderImages as $idx=>$sliderImage)
                    <div class='carousel-item has-background {{ $idx==0 ? 'is-active':null }}'>
                        <a href="{{ $sliderImage->link_to }}">
                            <img class="is-background" src="{{ $sliderImage->media->url }}" style="width: {{ $width }}; height: {{ $height }};" />
                        </a>
                        <{{ empty($sliderImage->html_tag) ? 'div' : $sliderImage->html_tag }} class="{{ empty($sliderImage->classes_name) ? null : $sliderImage->classes_name }}">
                            {{ empty($sliderImage->extra_html) ? null : $sliderImage->extra_html }}
                        </{{ empty($sliderImage->html_tag) ? 'div' : $sliderImage->html_tag }}>
                    </div>
                @endforeach
            </div>
            <div class="carousel-navigation {{ $slider->overlay }}">
                <div class="carousel-nav-left">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </div>
                <div class="carousel-nav-right">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="{{ $slider->wrapper_classes }}" {{ $slider->attributes_text }}>
        <div class="carousel carousel-animated carousel-animate-slide {{ $slider->images_per_frame>1 ? 'is-'.$slider->images_per_frame : null }}" data-autoplay="true" data-delay="{{ $slider->interval }}">
            <div class='carousel-container'>
                @foreach($sliderImages as $idx=>$sliderImage)
                    <div class='carousel-item has-background {{ $idx==0 ? 'is-active':null }}'>
                        @if($slider->images_per_frame>1)
                            <figure class="image {{ $ratio }}">
                                <img src="{{ $sliderImage->media->url }}" style="width: {{ $width }}px; height: {{ $height }}px;">
                            </figure>
                        @else
                            <a href="{{ $sliderImage->link_to }}">
                                <img class="is-background" src="{{ $sliderImage->media->url }}" style="width: {{ $width }}; height: {{ $height }};" />
                            </a>
                            <{{ empty($sliderImage->html_tag) ? 'div' : $sliderImage->html_tag }} class="{{ empty($sliderImage->classes_name) ? null : $sliderImage->classes_name }}">
                                    {{ empty($sliderImage->extra_html) ? null : $sliderImage->extra_html }}
                            </{{ empty($sliderImage->html_tag) ? 'div' : $sliderImage->html_tag }}>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="carousel-navigation {{ $slider->overlay }}">
                <div class="carousel-nav-left">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </div>
                <div class="carousel-nav-right">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
@endif
<?php } ?>