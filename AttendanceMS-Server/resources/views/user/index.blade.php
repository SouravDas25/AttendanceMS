@extends('layouts.dashboard')
@section('page_heading','Faculty')


@section('section')
<div class="row">
	<div class ="col-sm-8">
		<form class="form-horizontal" action="/{{ Route::current()->uri() }}" method="get">
			<div class="form-group">
				<div class="col-sm-12">
					<input type="text" class="form-control" name="search_text" placeholder="Search Faculty..">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-5">
					<!-- input type="hidden" name="_token" value="{{ csrf_token() }}"-->
					<button type="submit" class="btn btn-primary btn-block">
						<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
					</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-4">
		@include('widgets.panel_button',
            [   'pb_link'=> '/home/faculty/create',
                'pb_icon'=>"fa fa-facebook fa-5x",
                'pb_icon_ani'=>"animated shake",
                'pb_label'=>"Add A Faculty",
				'pb_heading' => count($users) ,
				'pb_sub_heading' => "Total Faculty !",
				'pb_fclass'=>"green",
                'pb_class'=>"panel-success"       ])
	</div>
</div>
<div class ="row">
	<div class ="col-sm-8">
		<span class="label label-default">Search Result For "{{ isset($st)? $st : "" }}"</span>
	</div>
</div>

@if (isset($users) )

<div class="row">
	<div class="col-md-12">
		@component('widgets.advance_table', array("table_hclass"=>"red",'table_class' => ' animated bounceInLeft'))
        @slot("table_heading","Users")
				<thead>
					<tr>
						<th>User Name</th>
						<th>Department</th>
						<th>Email</th>
						<th>Phone Number</th>
						<th>User Type</th>
						<th width="13%">Actions</th>
					</tr>
				</thead>
				<tbody>
					@if (count($users) > 0)
					
					@foreach ($users as $user)
					<tr>
						<td>{{ $user->user_name }}</td>
						<td>{{ $user->dept_name }}</td>
						<td>{{ $user->email }}</td>
						<td>{{ $user->phn_no }}</td>
						<td>{{ $user->user_type }}</td>
						<td class="text-center" >
						@include('widgets.action_box',
							['ab_edit'=>"home/faculty/update/". $user->user_id,
							'ab_delete'=>"home/faculty/delete/". $user->user_id])
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