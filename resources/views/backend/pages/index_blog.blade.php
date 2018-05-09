@extends('layouts.backend')
@section('content')
    <div id="">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.menu.blog') }} {{ trans('admin.mgr') }} ({{ $pages->total() }})
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/blog/add') }}"><i class="fa fa-plus"></i>&nbsp;{{ trans('admin.new.blog') }}</a>
            </div>
        </div>

        <div class="container">
            <table class="table full-width is-hoverable">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>页面中文Title</th>
                    <th>URI</th>
                    <th>SEO Keywords</th>
                    <th>SEO Description</th>
                    <th>Layout</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pages as $key=>$value)
                    <tr>
                        <td>
                            {!! $value->title !!}
                        </td>
                        <td>
                            {!! $value->title_cn !!}
                        </td>
                        <td>
                            <a href="{{ url('/page'.$value->uri) }}" target="_blank">Preview</a>
                        </td>
                        <td>
                            {!! $value->seo_keyword !!}
                        </td>
                        <td>
                            {!! $value->seo_description !!}
                        </td>
                        <td>{{ \App\Models\Utils\ContentTool::GetPageLayoutTypeByKey($value->layout) }}</td>
                        <td>
                            <a class="button is-small" href="{{ url('backend/blog/edit/'.$value->id) }}">
                                <i class="fa fa-edit"></i>&nbsp;Edit
                            </a>
                            <a class="button is-danger is-small btn-delete" href="{{ url('backend/blog/delete/'.$value->id) }}">
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