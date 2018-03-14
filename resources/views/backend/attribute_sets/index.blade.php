@extends('layouts.backend')

@section('content')
    <div>
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.menu.attribute_sets') }} {{ trans('admin.mgr') }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('backend/attribute-sets/add') }}">
                    <i class="fa fa-plus"></i>&nbsp;{{ trans('admin.new.attribute_sets') }}
                </a>
            </div>
        </div>
        <div class="container">
            <table class="table full-width is-hoverable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Parent</th>
                        <th>Attributes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($attributeSets as $key=>$value)
                    <tr class="align-middle">
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->parent ? $value->parent->name : null }}</td>
                        <td>
                            @foreach($value->attributes as $kid)
                                <span class="tag is-light">{{ $kid->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a class="button is-small" href="{{ url('backend/attribute-sets/edit/'.$value->id) }}">
                                <i class="fa fa-edit"></i>&nbsp;Edit
                            </a>
                            <a class="button is-small" href="{{ url('backend/attribute-sets/listing/'.$value->id) }}">
                                <i class="fa fa-edit"></i>&nbsp;Manage Attributes
                            </a>
                            <a class="button is-danger is-small btn-delete" href="{{ url('backend/attribute-sets/delete/'.$value->id) }}">
                                <i class="fa fa-trash"></i>&nbsp;Del
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $attributeSets->links() }}
        </div>
    </div>
@endsection
