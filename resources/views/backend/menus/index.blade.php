@extends('layouts.backend')
@section('content')
    <div id="">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.menu.menus') }} {{ trans('admin.mgr') }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/menus/add') }}"><i class="fa fa-plus"></i>&nbsp;{{ trans('admin.new.menu') }}</a>
            </div>
        </div>

        <div class="container">
            <table class="table full-width is-hoverable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>菜单中文名</th>
                    <th>Position</th>
                    <th>Level</th>
                    <th>Parent</th>
                    <th>URL</th>
                    <th>CSS</th>
                    <th>TAG</th>
                    <th>EXTRA</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($menus as $key=>$value)
                    <tr>
                        <td>
                            {{ $value->name }}
                        </td>
                        <td>
                            {{ $value->name_cn }}
                        </td>
                        <td>{{ $value->position }}</td>
                        <td>{{ $value->level }}</td>
                        <td>{{ $value->parent ? $value->parent->name : null }}</td>
                        <td>
                            <a href="{{ url($value->link_to) }}" target="_blank">Preview</a>
                        </td>
                        <td>{{ $value->css_classes }}</td>
                        <td>{{ $value->html_tag }}</td>
                        <td>{{ $value->extra_html }}</td>
                        <td>{{ $value->active ? 'Published' : 'Unpublished' }}</td>
                        <td>
                            <a class="button is-small" href="{{ url('backend/menus/edit/'.$value->id) }}">
                                <i class="fa fa-edit"></i>&nbsp;Edit
                            </a>
                            <a class="button is-danger is-small btn-delete" href="{{ url('backend/menus/delete/'.$value->id) }}">
                                <i class="fa fa-trash"></i>&nbsp;Del
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection