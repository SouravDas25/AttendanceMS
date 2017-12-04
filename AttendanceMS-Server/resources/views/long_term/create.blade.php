@extends('layouts.dashboard')
@section('page_heading','New Attendance' )
@section('section')	
<div class ="row">
	<div class ="col-sm-8">
		@section ('pane2_panel_title', $student->student_name)
    @section ('pane2_panel_body')
      <form class="form-horizontal" action='/home/long.term/create/submit' method="post">
				<fieldset>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="attn">No Of Classes Attended</label>  
				  <div class="col-md-4">
				  	<input name="attn" type="number" class="form-control input-md" required="" >
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-4 control-label" for="total">Total Classes</label>
				  <div class="col-md-4">
				  	<input name="total" type="number" class="form-control input-md" required="">
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  	<label class="col-md-4 control-label" for="sem_no">Semester</label>  
				  	<div class="col-md-4">
				  	<select name="sem_no" class="form-control" required="" >
				  		@foreach($sems as $sem) 
					      	<option value="{{{ $sem }}}" >
									 {{ Utility::ordinal_suffix($sem) }} Sem
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
				<input type="hidden" name="std_id" value="{{ $student->student_id }}">

				</fieldset>
			</form>        
		@endsection
    @include('widgets.panel', array('header'=>true, 'as'=>'pane2','class'=>'primary'))
	</div>
</div>

@stop
