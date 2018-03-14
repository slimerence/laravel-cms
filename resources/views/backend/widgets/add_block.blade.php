@extends('layouts.backend')

@section('content')
    <div id="">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.new.blocks') }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/widgets/blocks') }}"><i class="fa fa-plus"></i>&nbsp;Back</a>
            </div>
        </div>

        <div class="container">
            <form class="full-width" method="POST" action="{{ url('backend/blocks/save') }}">
                @csrf
                <input type="hidden" name="id">
                <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                        <input type="text" class="input{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus placeholder="Menu name in English: Required">
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="field">
                    <label class="label">Short Code</label>
                    <div class="control">
                        <input type="text" class="input{{ $errors->has('short_code') ? ' is-invalid' : '' }}" name="short_code" value="{{ old('short_code') }}" placeholder="Short Code: Required" required>
                        @if ($errors->has('short_code'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('short_code') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="field">
                    <label class="label">Location</label>
                    <div class="control">
                        <div class="select">
                            <select name="type">
                                <option value="{{ \App\Models\Widget\Block::$TYPE_GENERAL }}">General 在Page中使用</option>
                                <option value="{{ \App\Models\Widget\Block::$TYPE_LEFT }}">Left Side Bar 在左边栏中使用</option>
                                <option value="{{ \App\Models\Widget\Block::$TYPE_RIGHT }}">Right Side Bar 在右边栏中使用</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Order 排序</label>
                    <div class="control">
                        <input type="text" class="input{{ $errors->has('position') ? ' is-invalid' : '' }}" name="position" value="{{ old('position') }}" placeholder="Order 排序: Required" required>
                        @if ($errors->has('position'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('position') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="field">
                    <label class="label">Block Content</label>
                    <div class="control">
                        <textarea class="textarea" name="content" placeholder="Required: 只能填写HTML代码"></textarea>
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
