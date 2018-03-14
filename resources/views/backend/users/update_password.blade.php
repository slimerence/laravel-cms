@extends('layouts.backend')

@section('content')
    <div id="">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    Change Password: {{ $user->name ? $user->name : 'Add New' }}
                </h2>
            </div>
        </div>

        <div class="container">
            <form class="full-width" method="POST" action="{{ url('backend/update-password') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">
                <div class="columns">
                    <div class="column is-6">
                        <label class="label">Password <span class="has-text-danger">*</span></label>
                        <div class="control">
                            <input type="text" class="input{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="" required autofocus placeholder="New Password: Required">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="control">
                        <button type="submit" class="button is-link">
                            <i class="fa fa-upload"></i>&nbsp;Update password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
