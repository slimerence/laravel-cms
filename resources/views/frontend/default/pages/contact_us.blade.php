@extends(_get_frontend_layout_path('frontend'))
@section('content')
    {!! div_content(['class'=>'pl-20 pr-20 page-content-wrap']) !!}
    <br>
    <h1 class="is-size-1 has-text-centered is-uppercase">{{ trans('general.menu_contact') }}</h1>
    <br>
    {!! div_row(['id'=>'contact-us-app']) !!}
        <div class="column">
            <div class="box">
                <h2 class="is-size-3">Quote Online</h2>
                <hr>
                <form action="{{ url('contact-us') }}" method="post" id="contact-us-form">
                    {{ csrf_field() }}
                    <input type="hidden" name="user" value="{{ session('user_data.uuid') }}">
                    <div class="field">
                        <label class="label">Name</label>
                        <div class="control has-icons-left">
                            <input class="input" name="name" type="text" placeholder="Your Name" id="input-name" required>
                            <span class="icon is-small is-left">
                              <i class="fas fa-user"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Phone</label>
                        <div class="control has-icons-left">
                            <input class="input" name="mobile" type="text" placeholder="Your Phone #" id="input-phone" required>
                            <span class="icon is-small is-left">
                              <i class="fas fa-phone"></i>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control has-icons-left has-icons-right">
                            <input class="input" type="email" placeholder="Your Email" name="email" id="input-email" required>
                            <span class="icon is-small is-left">
                              <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Message</label>
                        <div class="control">
                            <textarea rows="6" class="textarea" placeholder="Say Something ..." id="input-message" name="message"></textarea>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <label class="checkbox">
                                <input type="checkbox" checked>
                                I agree to the <a href="{{ url('/terms') }}">terms and conditions</a>
                            </label>
                        </div>
                    </div>

                    <div class="field is-grouped">
                        <div class="control">
                            <button class="button is-link" id="submit-contact-us-btn">Submit</button>
                        </div>
                    </div>
                </form>
                <div class="notification is-primary" style="display: none;margin-top: 10px;" id="txt-on-success">
                    Your enquiry form has been saved, we will contact you very soon!
                </div>
                <div class="notification is-danger" style="display: none;margin-top: 10px;" id="txt-on-fail">
                    System is busy, please try again later!
                </div>
            </div>
        </div>
        <div class="column">
            <div class="box">
                <article class="media">
                    <div class="media-content">
                        <div class="content">
                            <h1>{{ trans('general.menu_contact') }} On {{ $siteConfig->contact_phone }}</h1>
                            <p class="is-size-5"><a style="color: black;" href="mailto:{{ $siteConfig->contact_phone }}">{{ $siteConfig->contact_phone }}</a></p>
                            <hr>
                            <?php
                            $fields = $config->getFillableArray();
                            ?>
                            @foreach($fields as $field)
                                @if(!empty($config->$field) && $config->isContactUsField($field))
                                    <p class="pl-10">
                                        {{ ucwords(str_replace('_',' ',$field)) }}:
                                        <strong>{{ $config->$field }}</strong>
                                        <br>
                                    </p>
                                @endif
                            @endforeach

                            @if($config->embed_map_code)
                                <hr>
                                {!! $config->embed_map_code !!}
                            @endif
                            @if(isset($leads) && count($leads)>0)
                                <h2>Testimonials</h2>
                                <hr>
                                <div class="testimonials-list">
                                    @foreach($leads as $lead)
                                        <p>
                                            <span class="has-text-link">{{ $lead->name }}:</span> {{ $lead->message }}
                                        </p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            </div>
        </div>
    {!! div_end() !!}
    {!! div_end() !!}
@endsection