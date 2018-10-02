@extends('layouts.dashboard')

@section('page_heading','Streams-Batch')

@section('section')

@if( Utility::is_user_admin() )
<div class ="row">
	<div class="col-sm-4">
		@include('widgets.panel_button',
            [   'pb_link'=> "/home/long.term/normalize",
                'pb_icon'=>"fa fa-bank fa-5x",
                'pb_icon_ani'=>"animated shake",
                'pb_label'=>"Normalize Database",
				'pb_fclass'=>"green"  ,
                'pb_class'=>"panel-success"       ])
	</div>
	<div class="col-sm-4">
	</div>
	<div class="col-sm-4">
	</div>
</div>
@endif

@if (isset($data) )
@component('widgets.card', ['card_class'=>'bg-warning animated bounceInRight'])
@slot("card_heading","Streams")
<ul class="collapsible popout" data-collapsible="accordion">
	@foreach($data as $dept)
	<li>
		<div class="collapsible-header red">
			<Strong>{{ $dept->name}}</Strong> - 
			{{ $dept->full_name }}
		</div>
		<div class="collapsible-body bg-danger" style="padding:5px" >
			<div class="list-group white-text">
				@foreach($dept->batches as $batch)
					<a href="{{ route('home.long.term.view',[$batch->batch_no]) }}" class="list-group-item ">
						<h4>
						{{ $batch->start_date }} -  
						{{ $batch->start_date + $batch->course_years }} Batch
						</h4>
					</a>
				@endforeach
			</div>
		</div>
	</li>
	@endforeach
</ul>
@endcomponent

@endif

@stop