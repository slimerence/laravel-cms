@extends(_get_frontend_layout_path('frontend'))
@section('content')
    <div class="content pt-40">
        {!! $page ? $page->rebuildContent() : null !!}
    </div>
@endsection