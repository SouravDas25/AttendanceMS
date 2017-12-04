@extends('layouts.dashboard')
@section('page_heading','New Class' )
@section('section')	
<div class ="row">
	<div class ="col-sm-8">
		@section ('pane2_panel_title', 'Create Classs')
        @section ('pane2_panel_body')
            <form class="form-horizontal" action='/{{Request::path()}}/submit'>
				<fieldset>

				<!-- Text input-->
				<div class="form-group">
				  	<label class="col-md-4 control-label" for="teacher_name">Teacher Name</label>  
				  	<div class="col-md-4">
				  	<select id="teacher_name" name="teacher_name" class="form-control" required="" >
				  		@foreach($teachers as $teacher)
					      	<option value="{{{ $teacher->id }}}"> {{ $teacher->name }}</option>
				      	@endforeach
				    </select>
				  	</div>
				</div>

				<div class="form-group">
				  	<label class="col-md-4 control-label" for="sub_code">Subject Code</label>  
				  	<div class="col-md-4">
				  	<select id="sub_code" name="sub_code" class="form-control" required="" >
				  		@foreach($subjects as $subject)
					      	<option value="{{{ $subject->id }}}"> {{ $subject->code }}</option>
				      	@endforeach
				    </select>
				  	</div>
				</div>

				<div class="form-group">
				  <label class="col-md-4 control-label" for=""></label>
				  <div class="col-md-4">
				    <button id="" name="" class="btn btn-inverse">Submit</button>
				  </div>
				</div>

				</fieldset>
			</form>        
		@endsection
        @include('widgets.panel', array('header'=>true, 'as'=>'pane2','class'=>'primary'))
	</div>
</div>

@stop