@extends('layouts.backend')

@section('content')
    <div id="">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    Dealer {{ empty($group->name) ? null : ':'.$group->name }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/groups') }}"><i class="fa fa-plus"></i>&nbsp;Back</a>
            </div>
        </div>

        <div class="container">
            <form class="full-width" method="POST" action="{{ url('backend/groups/save') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $group->id }}">

                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Name</label>
                            <div class="control">
                                <input type="text" class="input{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $group->name }}" required autofocus placeholder="经销商名称: Required">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">状态</label>
                            <div class="control">
                                <div class="select full-width">
                                    <select class="full-width" name="status">
                                        <option value="1" {{ $group->status ? 'selected' : null }}>上线</option>
                                        <option value="0" {{ $group->status ? null : 'selected' }}>暂停</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control">
                                <input type="email" class="input{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $group->email }}" required placeholder="Required: 电子邮件">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">Phone</label>
                            <div class="control">
                                <input type="text" class="input{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ $group->phone }}" required placeholder="Required: 联系电话">
                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Address</label>
                            <div class="control">
                                <div class=" full-width">
                                    <input type="text" class="input{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ $group->address }}" placeholder="Required: 公司地址">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">City</label>
                            <div class="control">
                                <div class=" full-width">
                                    <input type="text" class="input{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ $group->city }}" placeholder="Required: City/Suburb">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">State</label>
                            <div class="control">
                                <div class=" full-width">
                                    <input type="text" class="input{{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" value="{{ $group->state }}" placeholder="Required: State">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Postcode</label>
                            <div class="control">
                                <div class=" full-width">
                                    <input type="text" class="input{{ $errors->has('postcode') ? ' is-invalid' : '' }}" name="postcode" value="{{ $group->postcode }}" placeholder="Required: Postcode">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">Country</label>
                            <div class="control">
                                <div class=" full-width">
                                    <input type="text" class="input{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ $group->country ? $group->country : 'Australia' }}" placeholder="Required: 国家">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">Fax</label>
                            <div class="control">
                                <div class=" full-width">
                                    <input type="text" class="input{{ $errors->has('fax') ? ' is-invalid' : '' }}" name="fax" value="{{ $group->fax }}" placeholder="Required: 传真号码">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Extra Info</label>
                    <div class="control">
                        <textarea class="textarea" name="extra" placeholder="Optional: 其他信息">{{ $group->extra }}</textarea>
                    </div>
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
