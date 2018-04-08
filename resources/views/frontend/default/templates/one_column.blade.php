@extends(_get_frontend_layout_path('frontend'))
@section('content')

<div class="content pl-20 pr-20 page-content-wrap mb-20">
        <div class="page-title-wrap">
                <h1 class="is-size-1-desktop is-size-1-mobile" style="margin-top:0px; padding-top: 20px;">{!! 'cn'==app()->getLocale() ? $page->title_cn : $page->title !!}</h1>
        </div>
        {!! $page->rebuildContent() !!}
</div>
@endsection