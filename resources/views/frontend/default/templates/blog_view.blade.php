@extends(_get_frontend_layout_path('frontend'))
@section('content')
    <div class="page-title-wrap">
        <h1 class="is-size-1-desktop is-size-1-mobile">{{ $blog->title }}</h1>
    </div>
    <div class="page-content-wrap mb-20">
            <div class="columns">
                <div class="column">
                    <div class="content">
                        {!! $blog->content !!}
                    </div>
                </div>
            </div>
        <hr>
    </div>
@endsection