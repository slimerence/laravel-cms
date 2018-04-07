@extends('layouts.catalog')
@section('content')
    <div class="content pt-40">
        <div class="columns">
            <div class="column is-1"></div>
            <div class="column">
                @include('frontend.default.customers.elements.customer')
            </div>
            <div class="column">
                @include('frontend.default.customers.elements.wholesale')
            </div>
            <div class="column is-1"></div>
        </div>
    </div>
@endsection