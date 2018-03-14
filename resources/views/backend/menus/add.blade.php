@extends('layouts.backend')

@section('content')
    <div id="">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.new.menu') }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/menus/index') }}"><i class="fa fa-plus"></i>&nbsp;Back</a>
            </div>
        </div>

        <div class="container">
            <form class="full-width" method="POST" action="{{ url('backend/menus/save') }}">
                @csrf
                <input type="hidden" name="id">
                <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                        <input style="width: 42%;" type="text" class="input{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus placeholder="Menu name in English: Required">
                        <input style="width: 42%; margin-left: 10%;" type="text" class="input{{ $errors->has('name_cn') ? ' is-invalid' : '' }}" name="name_cn" value="{{ old('name_cn') }}" placeholder="菜单中文名">
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="field">
                    <label class="label">Position</label>
                    <div class="control">
                        <input type="text" class="input{{ $errors->has('email') ? ' is-invalid' : '' }}" name="position" value="{{ old('position') }}" placeholder="Optional: 菜单序号">
                        @if ($errors->has('position'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('position') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Status</label>
                            <div class="control">
                                <div class="select full-width">
                                    <select class="full-width" name="active">
                                        <option value="1">Published</option>
                                        <option value="0">Unpublished</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">Level</label>
                            <div class="control">
                                <div class="select full-width">
                                    <select class="full-width" name="level">
                                        <option value="1">Main Menu (一级菜单)</option>
                                        <option value="2">Sub menu(子菜单)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">选择上级菜单</label>
                            <div class="control">
                                <div class="select full-width">
                                    <select class="full-width" name="parent_id">
                                        <option value="0">None (无)</option>
                                        @foreach($menus as $menu)
                                            <option value="{{ $menu->id }}">{{ $menu->name }} {{ empty($menu->name_cn) ? null : '('.$menu->name_cn.')' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Link</label>
                    <div class="control">
                        <input type="text" class="input{{ $errors->has('link_to') ? ' is-invalid' : '' }}" name="link_to" value="{{ old('link_to') }}" placeholder="Optional: URL to link">
                        @if ($errors->has('link_to'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('link_to') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="field">
                    <label class="label">CSS Classes name</label>
                    <div class="control">
                        <textarea class="textarea" name="css_classes" placeholder="Optional: 需要附加的css规则类名">navbar-item</textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label">HTML Tag</label>
                    <div class="control">
                        <textarea class="textarea" name="html_tag" placeholder="Optional: 需要附加的HTML标签名"></textarea>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Extra HTML Code</label>
                    <div class="control">
                        <textarea class="textarea" name="extra_html" placeholder="Optional: 需要附加的HTML代码"></textarea>
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
