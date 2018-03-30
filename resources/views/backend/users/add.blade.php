@extends('layouts.backend')

@section('content')
    <div id="">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.edit.users') }}: {{ $user->name ? $user->name : 'Add New' }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/system-users') }}"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
            </div>
        </div>

        <div class="container">
            <form class="full-width" method="POST" action="{{ url('backend/users/save') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">
                <input type="hidden" name="status" value="1">
                <input type="hidden" name="role" value="{{ \App\Models\Utils\UserGroup::$OPERATOR }}">
                <div class="columns">
                    <div class="column">
                        <label class="label">Name <span class="has-text-danger">*</span></label>
                        <div class="control">
                            <input type="text" class="input{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}" required autofocus placeholder="Customer full name: Required">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="column">
                        <label class="label">Email <span class="has-text-danger">*</span></label>
                        <div class="control">
                            <input type="email" class="input{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $user->email }}" required placeholder="Customer's email: Required">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    @if(empty($user->password))
                        <div class="column">
                            <div class="field">
                                <label class="label">Password <span class="has-text-danger">*</span></label>
                                <div class="control">
                                    <input type="text" class="input{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ $user->password }}" required placeholder="Password: Required">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if(env('support_multiple_groups', false))
                    <div class="columns">
                        <div class="column is-6">
                            <div class="field">
                                <label class="label">所属经销商</label>
                                <div class="control">
                                    <div class="select full-width">
                                        <select class="full-width" name="group_id">
                                            <option value="0" {{ $user->group_id === 0 ? 'selected' : null }}>N.A</option>
                                            @foreach($groups as $group)
                                            <option value="{{ $group->id }}" {{ $user->group_id == $group->id ? 'selected' : null }}>{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="field is-horizontal">
                    <div class="control">
                        <button type="submit" class="button is-link">
                            <i class="fa fa-upload"></i>&nbsp;Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
