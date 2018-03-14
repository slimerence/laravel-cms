@if(isset($footer))
{!! $footer->content !!}
@else
<div class="footer d-flex justify-content-center">
    <div class="columns">
        <div class="column">
            <div class="p-2">Copyright © {{ date('Y') }} {{ str_replace('_',' ',env('APP_NAME')) }}. All rights reserved.</div>
        </div>
        <div class="column">
            <p class="has-text-right">
                @if(isset($siteConfig))
                    @if(!empty($siteConfig->facebook))
                        <a class="has-text-grey-darker" href="{{ $siteConfig->facebook }}" target="_blank"><i class="is-size-3 fab fa-facebook-square"></i></a>
                    @endif
                    @if(!empty($siteConfig->twitter))
                        <a class="has-text-grey-darker" href="{{ $siteConfig->twitter }}" target="_blank"><i class="is-size-3 fab fa-twitter-square"></i></a>
                    @endif
                    @if(!empty($siteConfig->linked_in))
                        <a class="has-text-grey-darker" href="{{ $siteConfig->linked_in }}" target="_blank"><i class="is-size-3 fab fa-linkedin"></i></a>
                    @endif
                    @if(!empty($siteConfig->google_plus))
                        <a class="has-text-grey-darker" href="{{ $siteConfig->google_plus }}" target="_blank"><i class="is-size-3 fab fa-google-plus-square"></i></a>
                    @endif
                    @if(!empty($siteConfig->instagram))
                        <a class="has-text-grey-darker" href="{{ $siteConfig->instagram }}" target="_blank"><i class="is-size-3 fab fa-instagram"></i></a>
                    @endif
                @endif
            </p>
        </div>
    </div>
</div>
@endif