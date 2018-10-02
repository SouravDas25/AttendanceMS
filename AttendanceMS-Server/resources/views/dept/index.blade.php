@extends('layouts.dashboard')



@section('page_heading','Departments')
@section('section')

<div class ="row">
	<div class ="col-sm-8">
		<form class="form-horizontal" action="{{ url('/'.Route::current()->uri()) }}" method="get">
			<div class="form-group">
				<div class="col-sm-12">
					<input type="text" class="form-control" name="search_text" placeholder="Search Depatment..">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-5">
					<!-- input type="hidden" name="_token" value="{{ csrf_token() }}" -->
					<button type="submit" class="btn btn-primary btn-block">
						<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
					</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-sm-4">
		@include('widgets.panel_button',
            [   'pb_link'=> url('/home/dept/create'),
                'pb_icon'=>"fa fa-plus fa-5x",
                'pb_icon_ani'=>"animated swing",
                'pb_label'=>"Add a Department",
				'pb_fclass'=>"orange",
                'pb_class'=>"panel-warning"       ])
	</div>
</div >
<div class ="row">
	<div class ="col-sm-8">
		<span class="label label-default">Search Result For "{{ isset($st)? $st : "" }}"</span>
	</div>
</div>

@if (isset($depts) )

<div class="row">
	<div class="col-md-12">

		@component('widgets.advance_table', array('table_class' => ' animated bounceInLeft'))
        @slot("table_heading","Departments")
				<thead>
					<tr>
						<th>Department</th>
						<th>Department Name</th>
						<th>Course Years</th>
						<th class="text-center" >Actions</th>
					</tr>
				</thead>
				<tbody>
					@if (count($depts) > 0)
					
					@foreach ($depts as $dept)
					<tr>
						<td>{{ $dept->dept_name }}</td>
						<td>{{ $dept->full_name }}</td>
						<td>{{ $dept->course_years }}</td>
						<td class="text-center">
							@include('widgets.action_box',
								['ab_edit'=>Route::current()->uri()."/update/".$dept->id,
								'ab_delete'=>Route::current()->uri()."/delete/".$dept->id])
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