@extends(_get_frontend_layout_path('frontend'))
@section('content')
    <div class="page-title-wrap">
        <h1 class="is-size-1-desktop is-size-1-mobile">News</h1>
    </div>
    <div class="page-content-wrap mb-20">
        @foreach($news as $key=>$post)
            <div class="columns">
                <div class="column is-one-quarter">
                    <a href="{{ url('/page'.$post->uri) }}">
                        <img class="image post-feature-image" src="{{ asset($post->feature_image) }}" alt="{{ $post->title }}">
                    </a>
                </div>
                <div class="column">
                    <h2 class="is-size-3">
                        <a href="{{ url('/page'.$post->uri) }}">{!! 'cn'==app()->getLocale() ? $post->title_cn : $post->title !!}</a>
                    </h2>
                    <p class="has-text-grey-light">{{ $post->updated_at }}</p>
                    <div class="post-teasing has-text-grey is-size-5">
                        {!! $post->teasing !!}
                    </div>
                </div>
            </div>
        @endforeach
        <hr>
        {{ $news->links() }}
    </div>
@endsection