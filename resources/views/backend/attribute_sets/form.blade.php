@extends('layouts.backend')

@section('content')
    <div class="content">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.new.attribute_sets') }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/attribute-sets') }}"><i class="fas fa-arrow-left"></i>&nbsp;Back</a>
            </div>
        </div>
        <div class="content">
            <form method="post" action="{{ url('backend/attribute-sets/save') }}">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $attributeSet->id }}">

                <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                        <input required type="text" class="input" name="name" value="{{ $attributeSet->name }}" placeholder="Name">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Parent</label>
                    <div class="select">
                    <select name="parent_id">
                        @foreach($parents as $key=>$parent)
                            <option value="{{ $parent->id }}" {{ $attributeSet->parent_id==$parent->id ? 'selected' :null }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
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
