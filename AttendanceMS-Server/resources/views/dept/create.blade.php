@extends('layouts.dashboard')
@section('page_heading','New Department' )
@section('section')	
<div class ="row">
	<div class ="col-sm-8">
		@section ('pane2_panel_title', 'Create Department')
    @section ('pane2_panel_body')
      <form class="form-horizontal" action='{{ route("home.dept.create.submit") }}' method="POST">
				<fieldset>

				<div class="form-group">
				  <label class="col-md-4 control-label" for="dept_name">Department Name</label>  
				  <div class="col-md-4">
				  <input id="dept_name" name="dept_name" type="text" placeholder="abbriviation" class="form-control input-md" required="" 
				   @include('widgets.tooltip', array('direction' => 'left', 'message'=>"Should Not Exceed more than 4 characters. Like 'BBA' , 'BCA' , 'MCA' , 'MBA'")) >
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-4 control-label" for="dept_full_name">Department Full Name</label>  
				  <div class="col-md-4">
				  <input id="dept_full_name" name="dept_full_name" type="text" placeholder="full name" class="form-control input-md" required=""
				  @include('widgets.tooltip', array('direction' => 'left', 'message'=>"Full Name of the Department like 'Master of Computer Application'"))> 
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-4 control-label" for="dept_year">No Of Course Years</label>  
				  <div class="col-md-4">
				  <input id="dept_year" name="dept_year" type="number" placeholder="Course Tenure" class="form-control input-md" required="" >
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-4 control-label" for=""></label>
				  <div class="col-md-4">
				    <button id="" name="" class="btn btn-primary">Submit</button>
				  </div>
				</div>

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				</fieldset>
			</form>        
		@endsection
    @include('widgets.panel', array('header'=>true, 'as'=>'pane2','class'=>'primary'))
	</div>
</div>

@stop