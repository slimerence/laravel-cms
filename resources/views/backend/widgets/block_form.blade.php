@extends('layouts.backend')

@section('content')
    <div class="content" id="blocks-manager-app">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    @if($block->id)
                        {{ trans('admin.edit.blocks') }}: {{ $block->name }}
                    @else
                        {{ trans('admin.new.blocks') }}
                    @endif
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/widgets/blocks') }}"><i class="fas fa-arrow-left"></i>&nbsp;Back</a>
            </div>
        </div>

        <div class="content">
            <form method="POST" action="{{ url('backend/blocks/save') }}">
                @csrf
                <input type="hidden" name="id">
                <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                        <input type="text" class="input{{ $errors->has('name') ? ' is-invalid' : '' }}" v-model="block.name" name="name" value="{{ old('name') }}" required autofocus placeholder="Block name in English: Required">
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
                        <input type="text" class="input{{ $errors->has('short_code') ? ' is-invalid' : '' }}" v-model="block.short_code" name="short_code" value="{{ old('short_code') }}" placeholder="Short Code: Required" required>
                        @if ($errors->has('short_code'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('short_code') }}</strong>
                            </span>
                        @endif
                    </div>
                    <p class="help">特殊Block: 产品详情顶部短码: {{ \App\Models\Widget\Block::PRODUCT_DESCRIPTION_KEY_TOP }}; 产品详情底部短码: {{ \App\Models\Widget\Block::PRODUCT_DESCRIPTION_KEY_BOTTOM }}</p>
                    <p class="help">特殊Block: 产品简介顶部短码: {{ \App\Models\Widget\Block::PRODUCT_SHORT_DESCRIPTION_KEY_TOP }}; 产品简介底部短码: {{ \App\Models\Widget\Block::PRODUCT_SHORT_DESCRIPTION_KEY_BOTTOM }}</p>
                </div>

                <div class="field">
                    <label class="label">Location (注: 特殊的短码, Location不生效, 可以随意点选)</label>
                    <div class="control">
                        <div class="select">
                            <select name="type" v-model="block.type">
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
                        <input type="text" class="input{{ $errors->has('position') ? ' is-invalid' : '' }}" v-model="block.position" name="position" value="{{ old('position') }}" placeholder="Order 排序: Required" required>
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
                        <vuejs-editor
                            ref="blockContentEditor"
                            class="rich-text-editor"
                            text-area-id="block-content-editor"
                            :original-content="block.content"
                            image-upload-url="/api/images/upload"
                            existed-images="/api/images/load-all"
                            short-codes-load-url="/api/widgets/load-short-codes"
                            placeholder="(必填) Block Content"
                        ></vuejs-editor>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="control">
                        <button type="submit" class="button is-link" v-on:click="saveBlock($event)">
                            <i class="fa fa-upload"></i>&nbsp;Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
