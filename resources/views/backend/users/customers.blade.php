@extends('layouts.backend')
@section('content')
    <div class="content">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.menu.customers') }} {{ trans('admin.mgr') }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/customers/add') }}"><i class="fa fa-plus"></i>&nbsp;{{ trans('admin.new.customers') }}</a>
            </div>
        </div>

        <div class="content">
            <table class="table full-width is-hoverable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Groups</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customers as $key=>$value)
                    <tr>
                        <td>
                            {{ $value->name }}
                        </td>
                        <td>
                            <a href="mailto:{{ $value->email }}">{{ $value->email }}</a>
                        </td>
                        <td>{{ $value->phone }}</td>
                        <td>
                            <span class="tag is-light">{{ $value->group->name }}</span>
                            @foreach($value->groups as $group)
                                <span class="tag is-light">{{ $group->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $value->address ? $value->addressText() : null }}</td>
                        <td>
                            {{ $value->status ? 'Active':'Suspend' }}
                        </td>
                        <td></td>
                        <td>
                            <a class="button is-small" href="{{ url('backend/customers/edit/'.$value->id) }}">
                                <i class="fa fa-edit"></i>&nbsp;Edit
                            </a>
                            <a class="button is-small" href="{{ url('backend/update-password?customer_id='.$value->uuid) }}">
                                <i class="fa fa-key"></i>&nbsp;Change Password
                            </a>
                            <a class="button is-danger is-small btn-delete" href="{{ url('backend/customers/delete/'.$value->uuid) }}">
                                <i class="fa fa-trash"></i>&nbsp;Del
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $customers->links() }}
        </div>
    </div>
@endsection