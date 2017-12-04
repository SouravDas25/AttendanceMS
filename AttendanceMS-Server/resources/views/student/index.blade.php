@extends('layouts.dashboard')

@section('page_heading','Students')

@section('section')
<div class ="row">
	<div class ="col-sm-8">
		<form class="form-horizontal" action="/{{ Route::current()->uri() }}" method="get">
			<div class="form-group">
				<div class="col-sm-12">
					<input type="text" class="form-control" name="search_text" placeholder="Search Student..">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-5">
					<button type="submit" class="btn btn-primary btn-block">
						<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
					</button>
				</div>
			</div>
		</form>
	</div>

	<div class="col-sm-4">
		@include('widgets.panel_button',
				[   'pb_link'=> "/home/student/create",
					'pb_icon'=>"fa fa-user-plus fa-5x",
					'pb_icon_ani'=>"animated shake",
					'pb_label'=>"Add a Student",
					'pb_heading' => count($students) ,
					'pb_sub_heading' => "Students ! ",
					'pb_fclass'=>"orange",
					'pb_class'=>"panel-warning"       ])
	</div>
</div>

<div class ="row">
	<div class ="col-sm-8">
		<span class="label label-default">Search Result For "{{ isset($st)? $st : "" }}"</span>
	</div>
</div>
@if (isset($students) )
<div class="row">
	<div class="col-md-12">
		@component('widgets.advance_table', array('table_class' => ' animated bounceInLeft'))
        @slot("table_heading","Students")
				<thead>
					<tr>
						<th>Roll No</th>
						<th>Name</th>
						<th>Department</th>
						<th>Current Year</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@if (count($students) > 0)
					@foreach ($students as $student)
					<tr>
						<td>{{ $student->student_roll }}</td>
						<td>{{ $student->student_name }}</td>
						<td>{{ $student->student_dept }}</td>
						<td>{{ Utility::ordinal_suffix($student->year) }} Year</td>
						<td class="text-center" >
							@include('widgets.action_box',["ab_edit"=>Route::current()->uri()."/update/" . $student->student_id,
							"ab_delete"=> Route::current()->uri()."/delete/" . $student->student_id])
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
    $('#dataTables-example').dataTable();
});
 </script>
@endsection