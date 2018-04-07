@extends('layouts.backend')

@section('content')
    <div id="" class="content">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    Brand {{ empty($brand->name) ? null : ':'.$brand->name }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/brands') }}"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
            </div>
        </div>

        <div class="content">
            <form class="full-width" method="POST" action="{{ url('backend/brands/save') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $brand->id }}">

                <div class="columns">
                    <div class="column is-6">
                        <div class="field">
                            <label class="label">Name</label>
                            <div class="control">
                                <input type="text" class="input{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $brand->name }}" required autofocus placeholder="品牌名称: Required">
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
                                        <option value="1" {{ $brand->status ? 'selected' : null }}>上线</option>
                                        <option value="0" {{ $brand->status ? null : 'selected' }}>下线</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">推广</label>
                            <div class="control">
                                <div class="select full-width">
                                    <select class="full-width" name="promotion">
                                        <option value="1" {{ $brand->promotion ? 'selected' : null }}>加入推广组</option>
                                        <option value="0" {{ $brand->promotion ? null : 'selected' }}>退出推广组</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <div class="file has-name">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="file" id="brand-image-input">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                          <i class="fas fa-upload"></i>
                                        </span>
                                        <span class="file-label">
                                          Choose an Image for this Brand
                                        </span>
                                    </span>
                                    <span class="file-name" id="file-name-text"></span>
                                    @if($brand->image_url)
                                        <figure class="image" style="width: 100px;margin-left: 12px;">
                                            <img src="{{ $brand->getImageUrl() }}">
                                        </figure>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Extra Info</label>
                    <div class="control">
                        <textarea class="textarea" name="extra_html" placeholder="Optional: 其他信息">{{ $brand->extra_html }}</textarea>
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
    <script type="application/javascript">
        var file = document.getElementById("brand-image-input");
        file.onchange = function(){
            if(file.files.length > 0)
            {
                document.getElementById('file-name-text').innerHTML = file.files[0].name;
            }
        };
    </script>
@endsection
