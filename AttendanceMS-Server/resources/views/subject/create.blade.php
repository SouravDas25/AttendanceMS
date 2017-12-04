@extends('layouts.dashboard')
@section('page_heading','New Subject' )
@section('section')	
<div class ="row">
	<div class ="col-sm-8">
		@section ('pane2_panel_title', 'Create Subject')
    @section ('pane2_panel_body')
      <form class="form-horizontal" action='/{{Request::path()}}/submit' method="post" >
				<fieldset>

				<!-- Text input-->

				<div class="form-group">
				  <label class="col-md-4 control-label" for="sub_code">Subject Code</label>  
				  <div class="col-md-4">
				  <input id="sub_code" name="sub_code" type="text" placeholder="Code" class="form-control input-md" required="" >
				  </div>
				</div>


				<div class="form-group">
				  <label class="col-md-4 control-label" for="sub_name">Subject Name</label>  
				  <div class="col-md-4">
				  <input id="sub_name" name="sub_name" type="text" placeholder="name" class="form-control input-md" required="" >
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  	<label class="col-md-4 control-label" for="sub_batch">Department</label>  
				  	<div class="col-md-4">
				  	<select id="sub_batch" name="sub_batch" class="form-control" required="" >
							<option disabled selected value> -- select your department -- </option>
				  		@foreach($arr as $batch)
					      	<option value="{{{ $batch->batch_id }}}">
									 {{ $batch->dept_name . " " .  Utility::ordinal_suffix($batch->sem_no) . " Sem"  }}
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
