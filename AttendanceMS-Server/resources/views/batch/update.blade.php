@extends('layouts.dashboard')
@section('page_heading','Update Batch' )
@section('section')	
<div class ="row">
	<div class ="col-sm-8">
		@section ('pane2_panel_title', 'Update Batch Details')
        @section ('pane2_panel_body')
        <form class="form-horizontal" action='submit' method="POST">
				<fieldset>

				<!-- Text input-->

				<div class="form-group">
				  <label class="col-md-4 control-label" for="bch_date">Batch Start Year</label>  
				  <div class="col-md-4">
						<input id="bch_date" name="bch_date" type="number" placeholder="YYYY" class="form-control" required 
							value="{{ $sb->start_date }}">
				  </div>
				</div>

				<input type='hidden' name="id" value="{{ $sb->batch_no }}"/>

				<!-- Text input-->
				<div class="form-group">
				  	<label class="col-md-4 control-label" for="bch_dept">Department</label>  
				  	<div class="col-md-4">
				  	<select id="bch_dept" name="bch_dept" class="form-control" required="">
				  		@foreach($arr as $dept)
					      	<option value="{{{ $dept->id }}}" {{ ( $sb->dept_id == $dept->id )? 'selected' : "" }}> {{ $dept->name }}</option>
				      	@endforeach
				    </select>
				  	</div>
				</div>

				<div class="form-group">
				  <label class="col-md-4 control-label" for=""></label>
				  <div class="col-md-4">
				    <button id="" name="" class="btn btn-primary">submit</button>
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
