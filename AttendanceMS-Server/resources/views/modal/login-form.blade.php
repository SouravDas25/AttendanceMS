<div id="modal1" class="modal">
    <div class="modal-content">
        <h4>Sign In</h4>
        <p>
            <form role="form" action="{{ Utility::rootUrl('/login.verify') }}" method="post" id="login-form">
                <div class="input-field">
                    <input class="validate" name="email" type="email" autofocus
                           value="{{{ (Session::has('login_email'))? Session::get('login_email'):'' }}}">
                    <label for="password" style="color:{{ Session::has('login_error') ?  'red'  : 'inherit' }} ">
                        Email {{ Session::has('login_error') ? "  Incorrect " : ""}}
                    </label>
                </div>
                <div class="input-field ">
                    <input class="validate" name="password" type="password" value="">
                    <label for="password" style="color:{{ Session::has('login_perror') ?  'red'  : 'inherit' }} ">
                        Password {{ Session::has('login_perror') ? " Incorrect " : ""}}
                    </label>
                </div>
        <p>
            <input id="remember" name="remember" type="checkbox">
            <label for="remember">Remember Me</label>
        </p>
        <div class="input-field">
            <a href="{{ url('/forgot_password') }}">Forgot Password ?</a>
        </div>
        {{ csrf_field() }}
        </p>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="modal-action modal-close btn btn-default
            waves-effect waves-green btn-flat" data-dismiss="modal">
            Close
        </button>
        <button class="btn btn-danger waves-effect waves-green btn-flat" onclick="login_submit()">
            Sign In
        </button>
    </div>
</div>