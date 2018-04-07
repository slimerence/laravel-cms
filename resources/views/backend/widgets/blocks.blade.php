@extends('layouts.backend')
@section('content')
    <div class="content">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.menu.blocks') }} {{ trans('admin.mgr') }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/blocks/add') }}"><i class="fa fa-plus"></i>&nbsp;{{ trans('admin.new.blocks') }}</a>
            </div>
        </div>

        <div class="content">
            <table class="table full-width is-hoverable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Short Code</th>
                    <th>Order</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($blocks as $key=>$value)
                    <tr>
                        <td>
                            <a href="{{ url('backend/blocks/edit/'.$value->id) }}">{{ $value->name }}</a>
                        </td>
                        <td>
                            {{ $value->short_code }}
                        </td>
                        <td>
                            {{ $value->position }}
                        </td>
                        <td>
                            {{ \App\Models\Widget\Block::GetTypeName($value->type) }}
                        </td>
                        <td>
                            <a class="button is-small" href="{{ url('backend/blocks/edit/'.$value->id) }}">
                                <i class="fa fa-edit"></i>&nbsp;Edit
                            </a>
                            <a class="button is-danger is-small btn-delete" href="{{ url('backend/blocks/delete/'.$value->id) }}">
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