@extends ('layouts.plane2')
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <br/><br/><br/>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @section ('signup_panel_title','Set Up Admin Account')
                @section ('signup_panel_body')
                    <form role="form" action="{{ route('signup.store') }}" method="post">
                        <fieldset>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="input-field">
                                <input class="validate" name="username" type="text" autofocus
                                       required="">
                                <label for="username">Admin User Name</label>
                            </div>
                            <div class="input-field">
                                <input class="validate" name="contact" type="tel">
                                <label for="contact">Contact Number</label>
                            </div>
                            <div class="input-field">
                                <input class="validate" name="email" type="email"
                                       required>
                                <label for="email">Email</label>
                            </div>
                            <div class="input-field">
                                <input class="validate" name="password" id="user_password"
                                       type="password" required="">
                                <label for="password">Password</label>
                            </div>
                            <div class="input-field">
                                <!-- label id="cnf_pwd" class="text-danger"> Password Missmatch</label -->
                                <input class="validate" name="cnf_password"
                                       id="cnf_pswd" type="password" required>
                                <label for="cnf_password">Confirm Password</label>
                            </div>


                            <input class="btn  btn-primary btn-block" type="submit" value="Create Account"/>

                        </fieldset>
                    </form>
                @endsection

                @include('widgets.panel', array('as'=>'signup', 'header'=>true))
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $(function () {
            $("#cnf_pswd").keyup(function () {
                    if ($("#user_password").val() != $("#cnf_pswd").val()) {
                        $("#cnf_pwd").show();
                    }
                    else {
                        $("#cnf_pwd").hide();
                    }
                }
            );
        });
    </script>

@endsection