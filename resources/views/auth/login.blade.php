@extends('layouts.admin_login')

@section('content')
<div class="columns {{ \Jenssegers\Agent\Facades\Agent::isPhone() ? '':null }} animated fadeInDown">
    <div class="column {{ \Jenssegers\Agent\Facades\Agent::isPhone() ? 'is-offset-1 is-10':'is-half-desktop is-offset-one-quarter-desktop' }}">
            <div class="panel">
                <div class="panel-heading">Login</div>

                <div class="panel-block bg-white">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label for="email" class="label">E-Mail Address</label>
                            </div>

                                <div class="control">
                                    <input id="email" type="email" class="input{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                        </div>

                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label for="password" class="label">Password</label>
                            </div>

                            <div class="control">
                                <input id="password" type="password" class="input{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="field is-horizontal">
                            <div class="field-label">
                                <!-- Left empty for spacing -->
                            </div>
                            <div class="control">
                                <label class="checkbox">
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                </label>
                            </div>
                        </div>

                        <div class="field is-horizontal">
                            <div class="field-label">
                                <!-- Left empty for spacing -->
                            </div>
                            <div class="control">
                                <button type="submit" class="button is-link">
                                    Login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</div>
@endsection
