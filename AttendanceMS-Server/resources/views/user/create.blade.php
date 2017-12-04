
@extends('layouts.dashboard')
@section('page_heading','New User')
@section('section')
<div class="row">
    <div class="col-sm-8">
        @section ('pane2_panel_title', 'Create User')
        @section ('pane2_panel_body')
        <form class="form-horizontal" action="{{{ isset($is_req)? 'submit':'create/submit' }}}" method="post">
            <fieldset>

            <!-- Form Name -->

            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="user_name">User Name</label>  
              <div class="col-md-4">
              <input id="user_name" name="user_name" type="text" placeholder="user name " class="form-control input-md" required="">  
              </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="user_email">Email</label>  
              <div class="col-md-4">
              <input id="user_email" name="user_email" type="email" placeholder="email" class="form-control input-md" required>
              </div>
            </div>

            <!-- Password input >
            <div class="form-group">
              <label class="col-md-4 control-label" for="user_password">Password</label>
              <div class="col-md-4">
                <input id="user_password" name="user_password" type="password" placeholder="password" class="form-control input-md" required="">
                <div id="pwd-info1" style="visibility:{{{ isset($is_req)?'visible':'hidden' }}};color:red">
                    Password and Confrm Password Did Not Match.
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="cnf-pswd">Confirm Password</label>
              <div class="col-md-4">
                <input id="cnf_pswd" name="cnf_pswd" type="password" placeholder="3 - 36" class="form-control input-md" required="">
                <div id="pwd-info2" style="visibility:{{{ isset($is_req)?'visible':'hidden' }}};color:red">
                    Password and Confrm Password Did Not Match.
                </div>
              </div>
            </div -->

            <!-- Button -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
              <label class="col-md-4 control-label" for=""></label>
              <div class="col-md-4">
                <button id="" name="" class="btn btn-primary" onclick="onSubmit_click()">Submit</button>
              </div>
            </div>

            </fieldset>
        </form>
        @endsection
        @include('widgets.panel', array('header'=>true, 'as'=>'pane2','class'=>'primary'))
    </div>
</div>
<script type="text/javascript">
function onSubmit_click()
{
    var b = confirm("Are You Sure ? An auto generated Email Will Be sent to the given address.");
    if(!b)
    {
        event.preventDefault();  
        returnToPreviousPage();
        return false;
    }
    MY_click_load_wait();
    return true;
}
/*
$(function(){
    $("#cnf_pswd").keyup( function () {
                if( $("#user_password").val() != $("#cnf_pswd").val() )
                {
                    $("#pwd-info2").css("visibility", "visible");
                    $("#pwd-info1").css("visibility", "visible");
                }
                else
                {
                    $("#pwd-info2").css("visibility", "hidden");
                    $("#pwd-info1").css("visibility", "hidden");
                }
            }
        );
});*/
</script>
@stop