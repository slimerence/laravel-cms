@extends(_get_frontend_layout_path('frontend'))
@section('content')
    {!! div_content(['class'=>'pl-20 pr-20 page-content-wrap']) !!}
        {!! $page->rebuildContent() !!}
    {!! div_end() !!}
@endsection