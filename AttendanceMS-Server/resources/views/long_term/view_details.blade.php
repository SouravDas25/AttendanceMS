@extends('layouts.dashboard') 



@section('page_heading','Student Details') 




@section('section')

<div class="row">
	<div class="col-md-12">
		@component('widgets.card', ['card_class'=>'bg-warning animated bounceInRight']) 
		@slot("card_heading")
		<h3>{{ $student->student_name }}</h3>
		<br>
		<small>
			{{ $batch->dept_name }} {{ $batch->start_date }} - {{ $batch->start_date + $batch->course_years }}
			<br> Roll No - {{ $student->student_roll }}
		</small>
		@endslot 
		@if (count($sem_result) > 0)
		<div class="row">
			<div class="col-md-6">
				<div id="chartContainer2" style="height: 370px; width: 100%;"></div>
			</div>
			<div class="col-md-6">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							<tr>
								<th>Semester</th>
								<th>Percentage</th>
								<th>Your Attendance</th>
								<th>Total Attendance</th>
								@if( Utility::is_user_admin() )
								<th>Actions</th>
								@endif
							</tr>
						</thead>
						<tbody>
							@foreach( $sem_result as $sem )
							<tr>
								<td>{{ Utility::ordinal_suffix($sem->sem_no) }}</td>
								<td>{{ ($sem->total) ? round($sem->attendance/$sem->total*100,2) : 0 }} %</td>
								<td>{{ $sem->attendance }}</td>
								<td>{{ $sem->total }}</td>
								@if( Utility::is_user_admin() )
								<td>
									@include('widgets.action_box',
									["ab_edit"=>"home/long.term/update/" . $student->student_id . "/" . $sem->sem_no, 
									"ab_delete"=>"home/long.term/delete/". $student->student_id. "/" . $sem->sem_no])
								</td>
								@endif
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		@endif 
		@endcomponent

	</div>
</div>
@if( Utility::is_user_admin() )
<div class="row">
	<div class="col-md-4 animated bounceInDown ">
		@include('widgets.panel_button', [ 'pb_link'=> "/home/long.term/create/".$student->student_id, 'pb_icon'=>"fa fa-user-plus
		fa-5x", 'pb_icon_ani'=>"animated shake", 'pb_label'=>"Add An Attendance", 'pb_fclass'=>"blue", 'pb_class'=>"panel-info"
		])
	</div>
</div>
@endif 
@endsection 





@section('page_script')
<script src="{{ asset("js/jquery.canvasjs.min.js ") }}" type="text/javascript"></script>
<script>
	$(document).ready(
  function (){
    myonload();
  }
)

function myonload() {
@if ( isset($sem_result) && count($sem_result) > 0)
var chart2 = new CanvasJS.Chart("chartContainer2",{
  theme: "light1", //"light2", "dark1", "dark2"
	animationEnabled: true,
	title:{
		text: "Total Attendance Of All Semester",
    fontSize: "20"
	},
  axisY: { 
		title: "Attendance"
	},
  axisX: { 
		title: "Semester"
	},legend: {
       verticalAlign: "top"  // "top" , "bottom"
  },
	data: [
			
			
			{
				type: "splineArea",
				legendText: "Attendance Percentage",
				yValueFormatString: "#,##0.##\"%\"",
				dataPoints: [
					@foreach($sem_result as $sem )
						{ label: "{{ Utility::ordinal_suffix($sem->sem_no) }}" ,  y: {{ Utility::percentage($sem->attendance,$sem->total) }} },
					@endforeach
				]
			}
	]
});
chart2.render();
$('#chartContainer2').resize(function(){
			chart2.render();
		});
@endif

}

</script>
@endsection