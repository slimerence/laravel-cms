@extends('layouts.backend')

@section('content')
    <div class="content">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.edit.customers') }}: {{ $customer->name ? $customer->name : 'Add New' }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/customers') }}"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
            </div>
        </div>

        <div class="content">
            <form method="POST" action="{{ url('backend/customers/save') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $customer->id }}">
                <input type="hidden" name="status" value="1">
                <div class="columns">
                    <div class="column">
                        <label class="label">Name <span class="has-text-danger">*</span></label>
                        <div class="control">
                            <input type="text" class="input{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $customer->name }}" required autofocus placeholder="Customer full name: Required">
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
                            <input type="email" class="input{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $customer->email }}" required placeholder="Customer's email: Required">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="column">
                        <label class="label">Phone <span class="has-text-danger">*</span></label>
                        <div class="control">
                            <input type="text" class="input{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ $customer->phone }}" required placeholder="Customer phone: Required">
                            @if ($errors->has('phone'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <label class="label">Address</label>
                        <div class="control">
                            <input type="text" class="input{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ $customer->address }}" placeholder="Address: optional">
                        </div>
                    </div>
                    <div class="column">
                        <label class="label">City</label>
                        <div class="control">
                            <input type="text" class="input{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ $customer->city }}" placeholder="City: optional">
                        </div>
                    </div>
                    <div class="column">
                        <label class="label">State</label>
                        <div class="control">
                            <input type="text" class="input{{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" value="{{ $customer->state }}" placeholder="State: optional">
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <label class="label">Postcode</label>
                        <div class="control">
                            <input type="text" class="input{{ $errors->has('postcode') ? ' is-invalid' : '' }}" name="postcode" value="{{ $customer->postcode }}" placeholder="Postcode: optional">
                        </div>
                    </div>
                    <div class="column">
                        <label class="label">Country</label>
                        <div class="control">
                            <input type="text" class="input{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ $customer->country }}" placeholder="Country: optional">
                        </div>
                    </div>
                    <div class="column">
                        <label class="label">Fax</label>
                        <div class="control">
                            <input type="text" class="input{{ $errors->has('fax') ? ' is-invalid' : '' }}" name="fax" value="{{ $customer->fax }}" placeholder="Fax: optional">
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Role</label>
                            <div class="control">
                                <div class="select full-width">
                                    <select class="full-width" name="role">
                                        <option value="{{ \App\Models\Utils\UserGroup::$GENERAL_CUSTOMER }}" {{ $customer->role == \App\Models\Utils\UserGroup::$GENERAL_CUSTOMER ? 'selected' : null }}>General</option>
                                        <option value="{{ \App\Models\Utils\UserGroup::$WHOLESALE_CUSTOMER }}" {{ $customer->role == \App\Models\Utils\UserGroup::$WHOLESALE_CUSTOMER ? 'selected' : null }}>Wholesale</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">Group</label>
                            <div class="control">
                                <div class="select full-width">
                                    <select class="full-width" name="group_id">
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}" {{ $customer->group_id == $group->id ? 'selected' : null }}>{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(empty($customer->password))
                        <div class="column">
                            <div class="field">
                                <label class="label">Password <span class="has-text-danger">*</span></label>
                                <div class="control">
                                    <input type="text" class="input{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ $customer->password }}" required placeholder="Password: Required">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

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
