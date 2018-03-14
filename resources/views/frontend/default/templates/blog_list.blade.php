@extends('layouts.frontend')
@section('content')
    <div class="page-title-wrap">
        <h1 class="is-size-1-desktop is-size-1-mobile">Blog</h1>
    </div>
    <div class="page-content-wrap mb-20">
        @foreach($posts as $key=>$post)
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
                    <div class="post-teasing">
                        {!! $post->teasing !!}
                    </div>
                </div>
            </div>
        @endforeach
            <hr>
        {{ $posts->links() }}
    </div>
@endsection