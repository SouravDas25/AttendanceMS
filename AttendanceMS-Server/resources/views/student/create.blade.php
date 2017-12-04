@extends('layouts.dashboard')
@section('page_heading','New Student' )
@section('section')	
<div class ="row">
	<div class ="col-sm-8">
		@section ('pane2_panel_title', 'Create Student')
    @section ('pane2_panel_body')
      <form class="form-horizontal" action='/home/student/create/submit' method="post">
				<fieldset>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="stud_name">Student Name</label>  
				  <div class="col-md-4">
				  <input id="stud_name" name="stud_name" type="text" placeholder="name" class="form-control input-md" required="" >
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-4 control-label" for="stud_roll">Student Roll No</label>  
				  <div class="col-md-4">
				  <input id="stud_roll" name="stud_roll" type="text" placeholder="Roll No" class="form-control input-md" required="" >
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  	<label class="col-md-4 control-label" for="stud_batch">Batch</label>  
				  	<div class="col-md-4">
				  	<select id="stud_batch" name="stud_batch" class="form-control" required="" >
				  		@foreach($arr as $batch) 
					      	<option value="{{{ $batch->batch_no }}}"  {{{ ( isset($bno) && ( $bno == $batch->batch_no) ) ? "Selected" :"" }}}  >
									 {{ $batch->dept_name . " " . Utility::ordinal_suffix($batch->current_year) }} Year
									</option>
				      @endforeach
				    </select>
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
