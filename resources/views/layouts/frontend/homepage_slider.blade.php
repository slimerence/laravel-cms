@if(file_exists(storage_path('app/public/uploads/videos/home.mp4')))
    <div class="container">
        <video id="homepage-video" class="is-invisible">
            <source src="{{ url('storage/uploads/videos/home.mp4') }}" type="video/mp4">
        </video>
        <div class="homepage-video-mask" id="homepage-video-mask">
            <h1 class="logo-text">Wren Estate</h1>
            <h2 class="slogan-text">We make good wines</h2>
            <div class="columns">
                <div class="column is-3 is-offset-one-quarter">
                    <a href="#" class="homepage-video-mask-btn">View Shop</a>
                </div>
                <div class="column is-3">
                    <a href="#" class="homepage-video-mask-btn">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
@elseif(isset($homeSlider) && $homeSlider)
    {!! $homeSlider->outputHtml() !!}
@endif