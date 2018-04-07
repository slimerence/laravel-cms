@extends('layouts.backend')
@section('content')
    <div class="content">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.menu.users') }} {{ trans('admin.mgr') }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/users/add') }}"><i class="fa fa-plus"></i>&nbsp;{{ trans('admin.new.users') }}</a>
            </div>
        </div>

        <div class="content">
            <table class="table full-width is-hoverable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    @if(env('support_multiple_groups', false))
                    <th>Dealer</th>
                    @endif
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $key=>$value)
                    <tr>
                        <td>
                            {{ $value->name }}
                        </td>
                        <td>
                            <a href="mailto:{{ $value->email }}">{{ $value->email }}</a>
                        </td>
                        <td>
                            {{ $value->status ? 'Active':'Suspend' }}
                        </td>
                        @if(env('support_multiple_groups', false))
                            <td>{{ $value->group_id ? $value->group->name : null }}</td>
                        @endif
                        <td>
                            <a class="button is-small" href="{{ url('backend/users/edit/'.$value->id) }}">
                                <i class="fa fa-edit"></i>&nbsp;Edit
                            </a>
                            <a class="button is-small" href="{{ url('backend/update-password?customer_id='.$value->uuid) }}">
                                <i class="fa fa-key"></i>&nbsp;Change Password
                            </a>
                            <a class="button is-danger is-small btn-delete" href="{{ url('backend/users/delete/'.$value->uuid) }}">
                                <i class="fa fa-trash"></i>&nbsp;Del
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
@endsection