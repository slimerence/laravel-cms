@extends('layouts.backend')

@section('content')
    <div id="" class="content">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('general.menu_payment_methods') }}
                </h2>
            </div>
        </div>

        @foreach($payment_methods as $key=>$payment_method)
        <div class="content m-3 border-box" >
            <form action="{{ url('/backend/payment-methods/save') }}" method="post" class="">
                <input type="hidden" value="{{ $payment_method->id }}" name="pm[id]">
                @csrf
                <div class="columns">
                    <div class="column">
                        <h3 class="{{ $payment_method->mode==\App\Models\Settings\PaymentMethod::MODE_LIVE ? 'has-text-success':'has-text-grey' }}">
                            {!! $payment_method->mode==\App\Models\Settings\PaymentMethod::MODE_LIVE ? '<i class="fas fa-check"></i>':null !!} &nbsp;- {{ $payment_method->name }}
                        </h3>
                    </div>
                    <div class="column">
                        <div class="field is-pulled-right">
                            <div class="control">
                                <button class="button">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Mode</label>
                            <div class="control">
                                <div class="select">
                                    <select name="pm[mode]">
                                        <option
                                                value="{{ \App\Models\Settings\PaymentMethod::MODE_OFF }}"
                                                {{ $payment_method->mode==\App\Models\Settings\PaymentMethod::MODE_OFF ? 'selected' : null }}
                                        >Off</option>
                                        <option
                                                value="{{ \App\Models\Settings\PaymentMethod::MODE_TEST }}"
                                                {{ $payment_method->mode==\App\Models\Settings\PaymentMethod::MODE_TEST ? 'selected' : null }}
                                        >Test mode</option>
                                        <option
                                                value="{{ \App\Models\Settings\PaymentMethod::MODE_LIVE }}"
                                                {{ $payment_method->mode==\App\Models\Settings\PaymentMethod::MODE_LIVE ? 'selected' : null }}
                                        >Live mode</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">API Token</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="API Token" name="pm[api_token]" value="{{ $payment_method->api_token }}">
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">API Secret</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="API Secret" name="pm[api_secret]" value="{{ $payment_method->api_secret }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column is-6">
                        <div class="field">
                            <label class="label">Callback URL for success</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="API Token" name="pm[hook_success]" value="{{ $payment_method->hook_success }}">
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label class="label">Callback URL for fail</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="API Token" name="pm[hook_error]" value="{{ $payment_method->hook_error }}">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endforeach
    </div>
@endsection
