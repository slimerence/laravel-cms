@extends('layouts.frontend')
@section('content')
    {!! $page ? $page->rebuildContent() : null !!}
@endsection