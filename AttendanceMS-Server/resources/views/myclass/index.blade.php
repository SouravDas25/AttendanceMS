@extends('layouts.dashboard')
@section('page_heading','MyClasses')

@section('section')
<div class ="row">
	<div class ="col-md-4 animated bounceInRight">
	@include('widgets.panel_button',
				[   'pb_link'=> "/home/class",
					'pb_icon'=>"	fa fa-forumbee fa-5x",
					'pb_icon_ani'=>"animated wobble",
					'pb_label'=>"Take a Class",
					'pb_heading' => $max_hc ,
					'pb_sub_heading' => "Max Attendance in Class !",
					'pb_fclass'=>"red",
					'pb_class'=>"panel-danger"       ])
	</div>
	<div class="col-md-4 animated bounceInDown">
	@include('widgets.panel_button',
				[   'pb_link'=> "/home/class",
					'pb_icon'=>"fa fa-bookmark fa-5x",
					'pb_icon_ani'=>"animated wobble",
					'pb_label'=>"Take a Class",
					'pb_heading' => $class_mark_cnt ,
					'pb_sub_heading' => "No Of Classes Marked !",
					'pb_fclass'=>"orange",
					'pb_class'=>"panel-warning"       ])
	</div>
	<div class="col-md-4 animated bounceInLeft">
	@include('widgets.panel_button',
				[   'pb_link'=> "/home/class",
					'pb_icon'=>"fa fa-user-plus fa-5x",
					'pb_icon_ani'=>"animated shake",
					'pb_label'=>"Take a Class",
					'pb_heading' => $class_cnt ,
					'pb_sub_heading' => "No Of Classes Taken !",
					'pb_class'=>"panel-success"       ])
	</div>
</div>
<div class ="row animated bounceInLeft">
	<div class ="col-sm-8">
		<span class="col-sm-4 label label-default"> {{ ($st)? "Search Result For " . $st : "Classes Of Last 30 days." }} </span>
	</div>
	
</div>

@if (isset($MyClassess) )

<div class="row animated bounceInLeft">
	<div class="col-md-12" >
		@component('widgets.advance_table', array('table_class' => ' animated bounceInLeft','table_hclass'=>"teal"))
        @slot("table_heading")
			
			<form class="" action="/{{ Route::current()->uri() }}" method="GET">
				<h2 class=" white-text"  >MyClasses</h2><br>
				<div class="row">
					<div class="input-field col-xs-10 col-md-6">
						<input type="text" class="validate white-text " name="search_text">
						<label for="search_text" class="white-text col-xs-10">Search Class ...</label>
					</div>
					<div class="input-field col-xs-2 col-md-6">
						<button type="submit" class="btn btn-danger waves-effect waves-light">
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						</button>
					</div>
				</div>
			</form>
		@endslot
			<thead>
				<tr>
					<th>Class Date</th>
					<th>Subject Name</th>
					<th>Batch</th>
					<th>Head Count</th>
					<th>Mark Count</th>
					<th>Topic</th>
					<th>Remarks</th>
					<th>Department</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
			@if (count($MyClassess) > 0)
				@foreach ($MyClassess as $db_class)
				<tr>
					<?php 
						$seconds = time() - strtotime($db_class->class_date) ;
						$days = floor($seconds / 86400);
						$seconds %= 86400;

						$hours = floor($seconds / 3600);
						$seconds %= 3600;

						$minutes = floor($seconds / 60);
						$seconds %= 60;
					?>
					<td style="min-width:88px">
						{{ date('d-M-Y',strtotime($db_class->class_date)) }} ({{  $days }} days ago )
					</td>
					<td>{{ $db_class->subject_name }}</td>
					<td>{{ Utility::ordinal_suffix($db_class->batch_current_year) }} Year</td>
					<td align="center" >{{ ($db_class->head_count)?$db_class->head_count:0 }}</td>
					<td>{{ $db_class->mark_count }}</td>
					<td>{{ $db_class->class_topic }}</td>
					<td>{{ $db_class->class_remarks }}</td>
					<td>{{ $db_class->dept_name }}</td>
					<td >
						<div class="btn-group btn-group-xs" role="group" style="min-width:120%;" >
							<a type="button" class="btn btn-info tool-btn"
							href="{{ route('home.myclass.update',[$db_class->active_day_id]) }}" data-toggle="tooltip" title="Edit" >
								<i class="fa fa-cog fa-fw"></i>
							</a>
							<a type="button" class="btn btn-danger tool-btn"
							href="{{ route('home.myclass.delete',[$db_class->active_day_id]) }}" data-toggle="tooltip" title="Delete"  >
								<i class="fa fa-minus fa-fw"></i>
							</a>
						</div>
					</td>
				</tr>
				@endforeach
			@endif
			</tbody>
		@endcomponent
	</div>
</div>
@endif
@endsection

@section('page_script')
<script>
$(document).ready(function () {
    $('#dataTables-example').dataTable( );
});
 </script>
@endsection