@extends ('layouts.dashboard')
@section('page_heading','Account Settings' )
@section('section')
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <form class="form-horizontal" method="POST" action="settings/user/submit">
                    <fieldset>

                        <!-- Form Name -->
                        <legend>User Edit</legend>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="name">Name</label>
                            <div class="col-md-5">
                                <input id="name" name="name" type="text" placeholder="" class="form-control input-md"
                                       required=""
                                       value="{{ isset($data)?$data->user_name:""}}">

                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="email">Email</label>
                            <div class="col-md-5">
                                <input id="email" name="email" type="text" placeholder="" class="form-control input-md"
                                       required=""
                                       value="{{ isset($data)?$data->email:""}}" readonly>

                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="phn">Phone Number</label>
                            <div class="col-md-5">
                                <input id="phn" name="phn" type="text" placeholder="" class="form-control input-md"
                                       value="{{ isset($data)?$data->phn_no:""}}">

                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for=""></label>
                            <div class="col-md-4">
                                <button id="" name="" class="btn btn-primary">Submit</button>
                            </div>
                        </div>

                    </fieldset>
                </form>

                <form class="form-horizontal" method="POST" action="settings/password/submit">
                    <fieldset>

                        <!-- Form Name -->
                        <legend>Password Edit</legend>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <!-- Password input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="current_pwd">Current Password</label>
                            <div class="col-md-5">
                                <input id="current_pwd" name="current_pwd" type="password" placeholder=""
                                       class="form-control input-md" required="">

                            </div>
                        </div>

                        <!-- Password input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="new_pwd">New Password</label>
                            <div class="col-md-5">
                                <input id="new_pwd" name="new_pwd" type="password" placeholder=""
                                       class="form-control input-md" required="">
                            </div>
                        </div>

                        <!-- Password input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="cnfrm_pwd">Re-Enter New Password</label>
                            <div class="col-md-5">
                                <input id="cnfrm_pwd" name="cnfrm_pwd" type="password" placeholder=""
                                       class="form-control input-md" required="">
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for=""></label>
                            <div class="col-md-4">
                                <button id="" name="" type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>

                    </fieldset>
                </form>


            </div>
        </div>
    </div>


    <script type="text/javascript">
        $(function () {
            $("#cnfrm_pwd").keyup(function () {
                    if ($("#new_pwd").val() != $("#cnfrm_pwd").val()) {
                        $("#cnfrm_pwd").parent().addClass("has-error");
                    }
                    else {
                        $("#cnfrm_pwd").parent().removeClass("has-error");
                    }
                }
            );
        });
    </script>

@stop