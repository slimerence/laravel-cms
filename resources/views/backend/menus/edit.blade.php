@extends('layouts.backend')

@section('content')
    <div class="content">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.edit.menu') }}: {{ $menu->name }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/menus/index') }}"><i class="fas fa-arrow-left"></i>&nbsp;Back</a>
            </div>
        </div>

        <div class="content">
            <form method="POST" action="{{ url('backend/menus/save') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $menu->id }}">
                <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                        <input style="width: 42%;" type="text" class="input{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $menu->name }}" required autofocus placeholder="Menu name in English: Required">
                        <input style="width: 42%; margin-left: 10%;" type="text" class="input{{ $errors->has('name_cn') ? ' is-invalid' : '' }}" name="name_cn" value="{{ $menu->name_cn }}" placeholder="菜单中文名">
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
                        <input type="text" class="input{{ $errors->has('email') ? ' is-invalid' : '' }}" name="position" value="{{ $menu->position }}" placeholder="Optional: 菜单序号">
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
                                        <option value="1" {{ $menu->active ? 'selected' : null }}>Published</option>
                                        <option value="0" {{ $menu->active ? null : 'selected' }}>Unpublished</option>
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
                                        <option value="1" {{ $menu->level == 1 ? 'selected' : null }}>Main Menu (一级菜单)</option>
                                        <option value="2" {{ $menu->level > 1 ? 'selected' : null }}>Sub menu(子菜单)</option>
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
                                        <option value="0" {{ $menu->level == 0 ? 'selected' : null }}>None (无)</option>
                                        @foreach($menus as $m)
                                            <option value="{{ $m->id }}"  {{ $menu->parent_id == $m->id ? 'selected' : null }}>{{ $m->name }} {{ empty($m->name_cn) ? null : '('.$m->name_cn.')' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Link</label>
                            <div class="control">
                                <input type="text" class="input{{ $errors->has('link_to') ? ' is-invalid' : '' }}" name="link_to" value="{{ $menu->link_to }}" placeholder="Optional: URL to link">
                                @if ($errors->has('link_to'))
                                    <span class="invalid-feedback">
                                <strong>{{ $errors->first('link_to') }}</strong>
                            </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">Link Type</label>
                            <div class="control">
                                <div class="select full-width">
                                    <select class="full-width" name="link_type">
                                        <option value="{{ \App\Models\Menu::TYPE_STATIC_CONTENT }}" {{ \App\Models\Menu::TYPE_STATIC_CONTENT == $menu->link_type ? 'selected' : null }}>Link to static content page</option>
                                        <option value="{{ \App\Models\Menu::TYPE_DYNAMIC_CONTENT }}" {{ \App\Models\Menu::TYPE_DYNAMIC_CONTENT == $menu->link_type ? 'selected' : null }}>Link to dynamic content</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">CSS Classes name</label>
                    <div class="control">
                        <textarea class="textarea" name="css_classes" placeholder="Optional: 需要附加的css规则类名">{{ $menu->css_classes }}</textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label">HTML Tag</label>
                    <div class="control">
                        <textarea class="textarea" name="html_tag" placeholder="Optional: 需要附加的HTML标签名">{{ $menu->html_tag }}</textarea>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Extra HTML Code</label>
                    <div class="control">
                        <textarea class="textarea" name="extra_html" placeholder="Optional: 需要附加的HTML代码">{{ $menu->extra_html }}</textarea>
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
