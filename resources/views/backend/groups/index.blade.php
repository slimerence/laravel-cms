@extends('layouts.backend')
@section('content')
    <div id="">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.menu.groups') }} {{ trans('admin.mgr') }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/groups/add') }}"><i class="fa fa-plus"></i>&nbsp;{{ trans('admin.new.groups') }}</a>
            </div>
        </div>

        <div class="container">
            <table class="table full-width is-hoverable">
                <thead>
                <tr>
                    <th>名称</th>
                    <th>联系电话</th>
                    <th>联系地址</th>
                    <th>传真</th>
                    <th>电子邮件</th>
                    <th>状态</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($groups as $key=>$value)
                    <tr>
                        <td>
                            {!! $value->name !!}
                        </td>
                        <td>
                            {!! $value->phone !!}
                        </td>
                        <td>
                            {{ $value->getAddressText() }}
                        </td>
                        <td>
                            {!! $value->fax !!}
                        </td>
                        <td>
                            <a href="mailto:{{ $value->email }}">{!! $value->email !!}</a>
                        </td>
                        <td>{!! $value->status ? '上线' : '暂停' !!}</td>
                        <td>
                            <a class="button is-small" href="{{ url('backend/groups/edit/'.$value->id) }}">
                                <i class="fa fa-edit"></i>&nbsp;Edit
                            </a>
                            <a class="button is-danger is-small btn-delete" href="{{ url('backend/groups/delete/'.$value->id) }}">
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