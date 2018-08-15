@extends('layouts.backend')
@section('content')
    <div id="">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.menu.pages') }} {{ trans('admin.mgr') }} ({{ $pages->total() }})
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/pages/add') }}"><i class="fa fa-plus"></i>&nbsp;{{ trans('admin.new.pages') }}</a>
            </div>
        </div>

        <div class="container">
            <table class="table full-width is-hoverable">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>SEO Keywords</th>
                    <th>SEO Description</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pages as $key=>$value)
                    <tr>
                        <td>
                            <a target="_blank" href="{{ url('/page'.$value->uri) }}">
                                <p>{!! $value->title !!}</p>
                                @if(!empty($value->title_cn))
                                    <p>中文标题: {{ $value->title_cn }}</p>
                                @endif
                            </a>
                        </td>
                        <td>
                            {!! $value->seo_keyword !!}
                        </td>
                        <td>
                            {!! $value->seo_description !!}
                        </td>
                        <td>
                            <a class="button is-small" href="{{ url('backend/pages/edit/'.$value->id) }}">
                                <i class="fa fa-edit"></i>&nbsp;Edit
                            </a>
                            <a class="button is-danger is-small btn-delete" href="{{ url('backend/pages/delete/'.$value->id) }}">
                                <i class="fa fa-trash"></i>&nbsp;Del
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $pages->links() }}
        </div>
    </div>
@endsection