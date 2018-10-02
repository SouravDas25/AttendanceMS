@extends('layouts.dashboard') 
@section('page_heading','Department - Streams') 
@section('section') 
@if (isset($depts) ) 

@component('widgets.card', ['card_class'=>'bg-warning animated bounceInRight'])
@slot("card_heading","Streams")

<ul class="collapsible popout" data-collapsible="accordion">
	@foreach($depts as $dept)
	<li>
		<div class="collapsible-header red">
			<Strong>{{$dept["dept_name"]}}</Strong> - 
			{{ $dept["dept_fn"]}}
		</div>
		<div class="collapsible-body bg-danger" style="padding:5px" >
			<ul class="collapsible" data-collapsible="accordion" >
				@foreach($dept["sems"] as $sem)
				<li >
					<div class="collapsible-header blue">
						<strong>{{ Utility::ordinal_suffix($sem["sem_no"])}}</strong> - Semester
					</div>
					<div class="collapsible-body bg-info">
						<div class="list-group " style="padding:5px">
							@foreach($sem["subjects"] as $subject)
							<a href="{{ route('home.class.subject',[$subject->subject_code]) }}" class="list-group-item ">
								<span class="badge red white-text">{{$subject->subject_code}}</span>
								<h4 class="">
									<strong>{{$subject->subject_code}}</strong>
									<br>
									<small>{{ $subject->subject_name }}</small>
								</h4>
							</a>
							@endforeach
						</div>
					</div>
				</li>
				@endforeach
			</ul> 
		</div>
	</li>
	@endforeach
</ul> 
@endcomponent

@endif 
@stop

