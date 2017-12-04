@extends('layouts.dashboard')
@section('page_heading', 'Update Student' )
@section('section')	
<div class ="row">
	<div class ="col-sm-8">
		@section ('pane2_panel_title', 'Update Student')
    @section ('pane2_panel_body')
      <form class="form-horizontal" action='submit' method="post">
				<fieldset>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="stud_name">Student Name</label>  
				  <div class="col-md-4">
				  <input id="stud_name" name="stud_name" type="text" placeholder="name" class="form-control input-md" required="" 
				  value="{{{ $std->student_name }}}">
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="stud_roll">Student Roll No</label>  
				  <div class="col-md-4">
				  <input id="stud_roll" name="stud_roll" type="text" placeholder="roll no" class="form-control input-md"
				  value="{{{ $std->student_roll }}}" > 
				  </div>
				</div>

				<input type='hidden' name="id" value="{{ $std->student_id }}"/>

				<!-- Select Basic -->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="stud_batch">Batch</label>
				  <div class="col-md-4">
				    <select id="stud_batch" name="stud_batch" class="form-control" value="{{{ $std->batch_no }}}" >
				      @foreach($arr as $batch)
					      	<option value="{{{ $batch->batch_no }}}">
									 	{{ $batch->dept_name . " " . $batch->start_date . " - " . ($batch->start_date + $batch->course_years) }}
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