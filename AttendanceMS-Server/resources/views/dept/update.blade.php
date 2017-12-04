@extends('layouts.dashboard')
@section('page_heading', isset($edit)? 'Update Department': 'New Department' )
@section('section')	
<div class ="row">
	<div class ="col-sm-8">
		@section ('pane2_panel_title', 'Update Department')
        @section ('pane2_panel_body')
            <form class="form-horizontal" action='submit' method="POST">
				<fieldset>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="dept_name">Department Name</label>  
				  <div class="col-md-6">
				  <input id="dept_name" name="dept_name" type="text" placeholder="abbriviation" class="form-control input-md" required="" 
				   @include('widgets.tooltip', array('direction' => 'left', 'message'=>"Should Not Exceed more than 4 characters. Like 'BBA' , 'BCA' , 'MCA' , 'MBA'")) 
				   {!! isset($edit)?"value =".$dept->dept_name:"" !!} >
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="dept_full_name">Department Full Name</label>  
				  <div class="col-md-6">
				  <input id="dept_full_name" name="dept_full_name" type="text" placeholder="full name" class="form-control input-md" required=""
				  @include('widgets.tooltip', array('direction' => 'left', 'message'=>"Full Name of the Department like Master of Computer Application"))
				  {!! ( isset($edit) ) ?"value ='".$dept->full_name."'":"" !!} > 
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-4 control-label" for="dept_year">No of Year</label>
				  <div class="col-md-6">
				  <input id="dept_year" name="dept_year" type="number" placeholder="abbriviation" class="form-control input-md" required=""
				  value="{{{ $dept->course_years }}}" >
				  </div>
				</div>

				<!-- Select Basic -->
				@if( isset($edit) )
				<input type="hidden" name="dept_id" value="{{ $dept->id }}">
				@endif

				<div class="form-group">
				  <label class="col-md-4 control-label" for=""></label>
				  <div class="col-md-6">
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