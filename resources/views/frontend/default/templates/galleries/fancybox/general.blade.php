@foreach($galleries as $shortCode=>$gallery)
    <?php
        $galleryItems = $gallery->galleryItems;
        $size = 'is-'.(12/$gallery->images_per_row);
    ?>
    <div class="columns the-gallery-wrapper {{ $gallery->wrapper_classes }}" id="gallery-{{ $shortCode }}" {{ $gallery->attributes_text }}>
        @foreach($galleryItems as $key=>$galleryItem)
        <div class="column {{ $size }}">
            <a href="{{ $galleryItem->media->url }}" data-fancybox data-caption="{{ $galleryItem->caption }}">
                <img src="{{ $galleryItem->media->url }}" alt="{{ $galleryItem->caption }}" />
            </a>
        </div>
        @endforeach
    </div>
@endforeach
