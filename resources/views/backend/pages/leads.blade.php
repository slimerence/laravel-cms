@extends('layouts.backend')
@section('content')
    <div class="content">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.menu.leads') }} {{ trans('admin.mgr') }}
                </h2>
            </div>
            <div class="column">
            </div>
        </div>

        <div class="container">
            <table class="table full-width is-hoverable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Product</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($leads as $key=>$value)
                    <tr>
                        <td>
                            {{ $value->name }}
                        </td>
                        <td>
                            {{ $value->phone }}
                        </td>
                        <td>
                            <a href="mailto:{{ $value->email }}">{{ $value->email }}</a>
                        </td>
                        <td>
                            {{ $value->product ? $value->product->name : null }}
                        </td>
                        <td>
                            {!! $value->message !!}
                        </td>
                        <td>
                            <a class="button is-danger is-small btn-delete" href="{{ url('backend/leads/delete/'.$value->id) }}">
                                <i class="fa fa-trash"></i>&nbsp;Del
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $leads->links() }}
        </div>
    </div>
@endsection