@extends('layouts.frontend')
@section('content')
    <div class="content pl-20 pr-20 page-content-wrap">
        {!! $page->rebuildContent() !!}
    </div>
@endsection