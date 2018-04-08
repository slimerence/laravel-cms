<div class="box content">
    <div class="content-title-line">
        <h3>I Already Have an Account</h3>
    </div>
    <div class="content-detail-wrap">
        <form method="post" action="{{ url('/frontend/customers/login') }}">
            {{ csrf_field() }}
            <input type="hidden" name="the_referer" value="{{ $the_referer }}">
            <div class="field">
                <label for="staticEmail" class="label">Email</label>
                <div class="control">
                    <input type="text" class="input" id="staticEmail" name="email" placeholder="email@example.com">
                </div>
                @if ($errors->has('email'))
                    <p class="help is-danger">{{ $errors->first('email') }}</p>
                @endif
            </div>
            <div class="field">
                <label for="inputPassword" class="label">Password</label>
                <div class="control">
                    <input type="password" class="input" id="inputPassword" name="password" placeholder="Password">
                </div>
                @if ($errors->has('password'))
                    <p class="help is-danger">{{ $errors->first('password') }}</p>
                @endif
            </div>
            <div class="columns">
                <div class="column">
                    <p><a class="is-danger" href="{{ url('frontend/customers/forget-password') }}">Forget Password</a></p>
                </div>
                <div class="column">
                    <button type="submit" class="button is-link is-pulled-right">Log Me In</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
</div>