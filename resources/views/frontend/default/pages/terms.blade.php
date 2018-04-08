@extends(_get_frontend_layout_path('frontend'))
@section('content')
    {!! $page ? $page->rebuildContent() : null !!}
@endsection