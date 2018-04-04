@extends('layouts.backend')
@section('content')
    <div id="">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.menu.brands') }} {{ trans('admin.mgr') }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/brands/add') }}"><i class="fa fa-plus"></i>&nbsp;{{ trans('admin.new.brands') }}</a>
            </div>
        </div>

        <div class="container">
            <table class="table full-width is-hoverable">
                <thead>
                <tr>
                    <th>名称</th>
                    <th>上线状态</th>
                    <th>是否推广</th>
                    <th>Logo图片</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($brands as $key=>$value)
                    <tr>
                        <td>
                            {!! $value->name !!}
                        </td>
                        <td>
                            {!! $value->status ? '<span class="tag is-success">上线</span>' : '<span class="tag is-danger">下线</span>' !!}
                        </td>
                        <td>
                            {!! $value->status ? '<span class="tag is-success">推广中</span>' : '<span class="tag is-light">未推广</span>' !!}
                        </td>
                        <td>
                            <figure class="image" style="width: 100px;">
                                <img src="{{ $value->getImageUrl() }}">
                            </figure>
                        </td>
                        <td>
                            <a class="button is-small" href="{{ url('backend/brands/edit/'.$value->id) }}">
                                <i class="fa fa-edit"></i>&nbsp;Edit
                            </a>
                            <a class="button is-danger is-small btn-delete" href="{{ url('backend/brands/delete/'.$value->id) }}">
                                <i class="fa fa-trash"></i>&nbsp;Del
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $brands->links() }}
        </div>
    </div>
@endsection