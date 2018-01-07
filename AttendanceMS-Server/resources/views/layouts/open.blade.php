@extends('layouts.plane3')


@section('style')
    <style>
        html {
            min-width: 280px !important;
        }

        body {
            background-color: #455a64;
        }

        .container {
            margin: 0;
            border: 0;
            padding-left: 15%;
            padding-right: 15%;
            width: 100%;
            min-width: 100%;
        }

        @media (max-width: 426px) {
            .container {
                padding-left: 5%;
                padding-right: 5%;
            }
        }
    </style>
    @yield('page_style')
@endsection



@section('body')
    <nav class=" grey lighten-4" role="navigation">
        <div class="nav-wrapper container">
            <a id="logo-container" href="{{ Utility::is_loged_in() ? Utility::rootUrl('/home') : Utility::rootUrl() }}"
               class="brand-logo" style="font-size:1.2em;">Attendance MS</a>
            <ul class="right hide-on-med-and-down">
                <li>
                    @if( Utility::is_loged_in() )
                        <a href="{{ Utility::rootUrl('/home') }}">
                            Dashboard
                        </a>
                    @else
                        <a onclick="$('#modal1').modal('open');">
                            Sign In
                        </a>
                    @endif
                </li>
            </ul>

            <ul id="nav-mobile" class="side-nav">
                <li>
                    @if( Utility::is_loged_in() )
                        <a href="{{ Utility::rootUrl('/home') }}">
                            Dashboard
                        </a>
                    @else
                        <a onclick="$('#modal1').modal('open');">
                            Sign In
                        </a>
                    @endif
                </li>
                <li>
                    <a href="#cya">
                        Check Attendance
                    </a>
                </li>
            </ul>
            <a href="#" data-activates="nav-mobile" class="button-collapse">
                <i class="material-icons">menu</i>
            </a>
        </div>
    </nav>

    @yield('page_content')

    <footer class="page-footer teal">
        <div class="container">
            <div class="row">
                <div class="col l6 s12">
                    <h5 class="white-text">Company Bio</h5>
                    <p class="grey-text text-lighten-4">We are a team of college students working on this project like
                        it's our full time job. Any amount would help support
                        and continue development on this project and is greatly appreciated.
                    </p>
                </div>
                <div class="col l3 s12">
                    <h5 class="white-text">Core</h5>
                    <ul>
                        <li>
                            <div class="chip">
                                <img src="{{ asset("/img/php_logo.png") }}">
                                Php
                            </div>
                        </li>
                        <li>
                            <div class="chip">
                                <img src="{{ asset("/img/html_logo.png") }}">
                                Html 5
                            </div>
                        </li>
                        <li>
                            <div class="chip">
                                <img src="{{ asset("/img/css_logo.png") }}">
                                Css 3
                            </div>
                        </li>
                        <li>
                            <div class="chip">
                                <img src="{{ asset("/img/javascript_logo.png") }}">
                                Javascript ES6
                            </div>
                        </li>
                        <li>
                            <div class="chip">
                                <img src="{{ asset("/img/laravel_logo.jpg") }}">
                                Laravel
                            </div>
                        </li>
                        <li>
                            <div class="chip">
                                <img src="{{ asset("/img/mysql_logo.png") }}">
                                MySqL
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col l3 s12">
                    <h5 class="white-text">Supporting</h5>
                    <ul>
                        <li>
                            <div class="chip">
                                <img src="{{ asset("/img/ajax_logo.png") }}">
                                Ajax
                            </div>
                        </li>
                        <li>
                            <div class="chip">
                                <img src="{{ asset("/img/bootstrap_logo.png") }}">
                                Bootstrap
                            </div>
                        </li>
                        <li>
                            <div class="chip">
                                <img src="{{ asset("/img/apache_logo.gif") }}">
                                Apache
                            </div>
                        </li>
                        <li>
                            <div class="chip">
                                <img src="{{ asset("/img/gmail_logo.png") }}">
                                Gmail
                            </div>
                        </li>
                        <li>
                            <div class="chip">
                                <img src="{{ asset("/img/jquery_logo.gif") }}">
                                Jquery
                            </div>
                        </li>
                        <li>
                            <div class="chip">
                                <img src="{{ asset("/img/materialize_logo.jpg") }}">
                                Materialize Css
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            <div class="container">
                Made by
                <a class="brown-text text-lighten-3" href="#">Sourav Das</a>
            </div>
        </div>
    </footer>

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
                <a href="{{ Utility::rootUrl('/forgot_password') }}">Forgot Password ?</a>
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
            <button class="btn btn-danger waves-effect waves-green btn-flat" onclick="login_submit()"/>
            Sign In
            </button>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function () {
            $('.modal').modal();
            @if(Session::has('login_perror'))
            $('#modal1').modal('open');
            @endif
        });

        function login_submit() {
            MY_click_load_wait();
            $('#login-form').submit();
        }
    </script>
    @yield('page_script')
@endsection
