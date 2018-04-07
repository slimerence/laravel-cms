<div class="box content">
    <div class="content-title-line">
        <h3>I Already Have an Account</h3>
    </div>
    <div class="content-detail-wrap">
        <form method="post" action="{{ route('login') }}">
            {{ csrf_field() }}
            <input type="hidden" name="the_referer" value="{{ $the_referer }}">
            <div class="field">
                <label for="staticEmail" class="label">Email</label>
                <div class="control">
                    <input type="text" class="input" id="staticEmail" name="email" placeholder="email@example.com">
                </div>
            </div>
            @if ($errors->has('email'))
                <div class="row">
                    <div class="col">
                        <p class="text-center text-danger mt-1">
                            <strong>{{ $errors->first('email') }}</strong>
                        </p>
                    </div>
                </div>
            @endif
            <div class="field">
                <label for="inputPassword" class="label">Password</label>
                <div class="control">
                    <input type="password" class="input" id="inputPassword" name="password" placeholder="Password">
                </div>
            </div>
            @if ($errors->has('email'))
                <div class="row">
                    <div class="col">
                        <p class="text-center text-danger mt-1">
                            <strong>{{ $errors->first('password') }}</strong>
                        </p>
                    </div>
                </div>
            @endif
            <div class="columns">
                <div class="column">
                    <p><a class="is-danger" href="{{ url('password/reset') }}">Forget Password</a></p>
                </div>
                <div class="column">
                    <button type="submit" class="button is-link is-pulled-right">Log Me In</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
</div>