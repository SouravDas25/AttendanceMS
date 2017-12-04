@extends ('layouts.plane')
@section ('body')
<div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
            <br /><br /><br />
            <ul>
			    @foreach($errors->all() as $error)
			        <li>{{ $error }}</li>
			    @endforeach
			</ul>
               @section ('signup_panel_title','Set Up Admin Account')
               @section ('signup_panel_body')
                <form role="form" action="signup/store" method="post" >
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="User Name" name="username" type="text" required="">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Contact Number" name="contact" type="tel" >
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="email" name="email" type="email" autofocus required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Password" name="password" id="user_password" type="password" required="">
                        </div>
                        <div class="form-group">
                            <label  id="cnf_pwd" class="text-danger" > Password  Missmatch</label>
                            <input class="form-control" placeholder="Confirm Password" name="cnf_password" id="cnf_pswd" type="password" required="">
                        </div>
                        
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input class="btn btn-lg btn-success btn-block" type="submit" value = "Create Account" />
                        
                    </fieldset>
                </form>
                @endsection
                
                @include('widgets.panel', array('as'=>'signup', 'header'=>true))
            </div>
        </div>
    </div>


<script type="text/javascript">
$(function(){
    $("#cnf_pswd").keyup( function() {
                if( $("#user_password").val() != $("#cnf_pswd").val() )
                {
                    $("#cnf_pwd").show();
                }
                else
                {
                    $("#cnf_pwd").hide();
                }
            }
        );
});
</script>

@stop