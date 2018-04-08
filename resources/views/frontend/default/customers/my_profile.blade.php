@extends(_get_frontend_layout_path('catalog'))
@section('content')
    <div class="container pt-40 pl-20 pr-20" id="user-profile-manager-app">
        <br>
        <h2 class="is-size-4">
            My Profile
        </h2>
        <hr>
        <div class="content">
            <div class="columns">
                <div class="column is-3 panel">
                    <div class="content-title-line">
                        <h3 class="is-size-5 pl-10"><i class="far fa-check-square"></i>&nbsp;My Shipping Details</h3>
                    </div>
                    <div class="content-title-line">
                        <h3 class="is-size-5 pl-10">
                            <a href="{{ url('frontend/my_orders/'.session('user_data.uuid')) }}">Orders History</a>
                        </h3>
                    </div>
                </div>

                <div class="column is-9 content-block content-two-third-right">
                    <div class="content-title-line">
                        <h3>Shipping Address</h3>
                    </div>

                    <div class="content-detail-wrap">
                        <div class="content-line">
                            <label><i class="fas fa-map-signs has-text-danger"></i></label>
                            <label class="value">{{ $user->addressText() }}</label>
                        </div>
                    </div>

                    <div class="content-title-line">
                        <br>
                        <h3>My Contact Detail</h3>
                    </div>
                    <div class="content-detail-wrap">
                        <form method="post" action="{{ url('frontend/my_profile') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $user->uuid }}">
                            <div class="field">
                                <label for="staticEmail" class="label">Email</label>
                                <div class="control">
                                    <input type="text" readonly class="input" value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Name</label>
                                <div class="control">
                                    <input type="text" name="name" class="input"  placeholder="Full name" value="{{ $user->name }}">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Phone</label>
                                <div class="control">
                                    <input type="text" name="phone" class="input"  placeholder="Phone" value="{{ $user->phone }}">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Address</label>
                                <input type="text" name="address" class="input" placeholder="1234 Main St" value="{{ $user->address }}">
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <div class="field">
                                        <label class="label">City</label>
                                        <input type="text" class="input" value="{{ $user->city }}"  name="city">
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="field">
                                        <label for="inputZip" class="label">Postcode</label>
                                        <input type="text" name="postcode" class="input" placeholder="Postcode" value="{{ $user->postcode }}">
                                    </div>
                                </div>
                                <div class="column">
                                    <label class="label">State</label>
                                    <div class="select">
                                        <select name="state">
                                            @foreach(\App\Models\Utils\OptionTool::States() as $key=>$value)
                                                <option value="{{ $key }}" {{ $key==$user->state ? 'select' : null }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="button is-link is-pulled-right">Update Address</button>
                        </form>
                    </div>

                    <div class="is-clearfix"></div>
                    <hr>
                    <div class="content-title-line">
                        <h3>Manage Password</h3>
                    </div>
                    <div class="content-detail-wrap">
                        <form method="post" action="{{ url('frontend/update_password') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $user->uuid }}">

                            <div class="field">
                                <label class="label">New Password</label>
                                <div class="control">
                                    <input type="password" class="input" name="new_password"  placeholder="New Password" value="">
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Confirm Password</label>
                                <div class="control">
                                    <input type="password" class="input" name="new_password_confirm" placeholder="Confirm Password" value="">
                                </div>
                            </div>

                            <button type="submit" class="button is-link is-pulled-right">Update Password</button>
                        </form>
                    </div>
                    <div class="is-clearfix"></div>
                    <br>
                </div>
            </div>
        </div>
    </div>
@endsection