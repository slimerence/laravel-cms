<div class="box content">
    <div class="content-title-line">
        <h3>I am a Wholesaler</h3>
    </div>
    <div class="content-detail-wrap">
        <form method="post" action="{{ url('frontend/wholesale/login') }}">
            {{ csrf_field() }}
            <input type="hidden" name="the_referer" value="{{ $the_referer }}">
            <div class="field">
                <label class="label">Account #</label>
                <div class="control">
                    <input type="text" class="input" name="account_name" placeholder="Account #">
                </div>
            </div>
            <div class="field">
                <label class="label">Password</label>
                <div class="control">
                    <input type="password" class="input" name="password" placeholder="Password">
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <p><a class="is-link" href="{{ url('password/reset') }}">Forget Password</a></p>
                </div>
                <div class="column">
                    <button type="submit" class="button is-success is-pulled-right">Wholesaler Log In</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
</div>