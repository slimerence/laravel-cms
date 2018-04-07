@extends('layouts.backend')
@section('content')
    <div class="content">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    Site Settings
                </h2>
            </div>
            <div class="column">
            </div>
        </div>

        <div class="content">
            <form method="POST" action="{{ url('backend/configuration/save') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $config->id }}">
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Contact Phone</label>
                            <div class="control">
                                <input type="text" class="input" name="contact_phone" value="{{ $config->contact_phone }}" autofocus>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Contact Email</label>
                            <div class="control">
                                <input type="text" class="input" name="contact_email" value="{{ $config->contact_email }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Contact Fax</label>
                            <div class="control">
                                <input type="text" class="input" placeholder="Optional" name="contact_fax" value="{{ $config->contact_fax }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Address</label>
                            <div class="control">
                                <input type="text" class="input" placeholder="Optional" name="contact_address" value="{{ $config->contact_address }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Contact Person</label>
                            <div class="control">
                                <input type="text" class="input" placeholder="Optional" name="contact_person" value="{{ $config->contact_person }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Latitude</label>
                            <div class="control">
                                <input type="text" class="input" placeholder="Optional" name="latitude" value="{{ $config->latitude }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Longitude</label>
                            <div class="control">
                                <input type="text" class="input" placeholder="Optional" name="longitude" value="{{ $config->longitude }}">
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">Website Logo</label>
                            <div class="file has-name">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="file" id="the-logo-file">
                                    <span class="file-cta">
                                      <span class="file-icon">
                                        <i class="fas fa-upload"></i>
                                      </span>
                                      <span class="file-label">
                                        Choose a file…
                                      </span>
                                    </span>
                                    <span id="logo-file-name" class="file-name">
                                        {{ empty($config->logo) ? 'No Logo' : 'logo.jpg' }}
                                    </span>
                                </label>
                                <div class="is-pulled-right" style="margin-left: 10px;">
                                    @if($config->logo)
                                        <img src="{{ $config->logo }}" alt="" style="height: 80px; margin-left: 20px; margin-top: -45px;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="field" style="margin-top: -5px;">
                            <label class="label">Website Logo dark</label>
                            <div class="file has-name">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="image" id="the-logo-dark-file">
                                    <span class="file-cta">
                                      <span class="file-icon">
                                        <i class="fas fa-upload"></i>
                                      </span>
                                      <span class="file-label">
                                        Choose a file…
                                      </span>
                                    </span>
                                    <span id="logo-dark-file-name" class="file-name">
                                        {{ empty($config->logo_dark) ? 'No Logo' : 'logo_dark.jpg' }}
                                    </span>
                                </label>

                                @if($config->logo_dark)
                                <div class="is-pulled-right" style="background-color: black;margin-top: -45px;height: 80px;margin-left: 10px;">
                                    <img src="{{ $config->logo_dark }}" alt="" style="height: 80px; margin-left: 20px;">
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Multi-Language Support</label>
                            <div class="control">
                                <div class="select">
                                    <select name="multi_language">
                                        <option value="0" {{ $config->multi_language ? null : 'selected' }}>English Only</option>
                                        <option value="1" {{ $config->multi_language ? 'selected' : null }}>中英双语</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="field is-expanded">
                            <label class="label">Facebook</label>
                            <div class="field has-addons">
                                <p class="control">
                                    <a class="button is-static">
                                        https://
                                    </a>
                                </p>
                                <p class="control is-expanded">
                                    <input type="text" class="input" placeholder="Optional" name="facebook" value="{{ $config->facebook }}">
                                </p>
                            </div>
                        </div>

                        <div class="field is-expanded">
                            <label class="label">Twitter</label>
                            <div class="field has-addons">
                                <p class="control">
                                    <a class="button is-static">
                                        https://
                                    </a>
                                </p>
                                <p class="control is-expanded">
                                    <input type="text" class="input" placeholder="Optional" name="twitter" value="{{ $config->twitter }}">
                                </p>
                            </div>
                        </div>

                        <div class="field is-expanded">
                            <label class="label">Google+</label>
                            <div class="field has-addons">
                                <p class="control">
                                    <a class="button is-static">
                                        https://
                                    </a>
                                </p>
                                <p class="control is-expanded">
                                    <input type="text" class="input" placeholder="Optional" name="google_plus" value="{{ $config->google_plus }}">
                                </p>
                            </div>
                        </div>

                        <div class="field is-expanded">
                            <label class="label">Instagram</label>
                            <div class="field has-addons">
                                <p class="control">
                                    <a class="button is-static">
                                        https://
                                    </a>
                                </p>
                                <p class="control is-expanded">
                                    <input type="text" class="input" placeholder="Optional" name="instagram" value="{{ $config->instagram }}">
                                </p>
                            </div>
                        </div>

                        <div class="field is-expanded">
                            <label class="label">LinkedIn</label>
                            <div class="field has-addons">
                                <p class="control">
                                    <a class="button is-static">
                                        https://
                                    </a>
                                </p>
                                <p class="control is-expanded">
                                    <input type="text" class="input" placeholder="Optional" name="linked_in" value="{{ $config->linked_in }}">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Embed Map Code</label>
                            <div class="control">
                                <textarea class="textarea" name="embed_map_code" placeholder="Optional: 只能填写HTML代码">{{ $config->embed_map_code }}</textarea>
                            </div>
                        </div>
                        <div class="field is-horizontal">
                            <div class="control">
                                <button type="submit" class="button is-link">
                                    <i class="fa fa-upload"></i>&nbsp;Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="application/javascript">
        var file = document.getElementById("the-logo-file");
        var fileDark = document.getElementById("the-logo-dark-file");
        file.onchange = function(){
            if(file.files.length > 0)
            {
                document.getElementById('logo-file-name').innerHTML = file.files[0].name;
            }
        };
        fileDark.onchange = function(){
            if(fileDark.files.length > 0)
            {
                document.getElementById('logo-dark-file-name').innerHTML = fileDark.files[0].name;
            }
        };
    </script>
@endsection