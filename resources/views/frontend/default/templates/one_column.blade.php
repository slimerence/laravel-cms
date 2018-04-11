@extends(_get_frontend_layout_path('frontend'))
@section('content')
<div class="columns mt-20">
        <div class="column is-1"></div>
        <div class="column">
                <div class="content pt-40">
                        <div class="page-title-wrap">
                                <h1 class="is-size-1-desktop is-size-1-mobile" style="margin-top:0px; padding-top: 20px;">
                                        {!! 'cn'==app()->getLocale() ? $page->title_cn : $page->title !!}
                                </h1>
                        </div>
                        {!! $page->rebuildContent() !!}
                </div>
        </div>
        <div class="column is-1"></div>
</div>
@endsection