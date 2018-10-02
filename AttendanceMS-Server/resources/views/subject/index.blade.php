@extends('layouts.dashboard')
@section('page_heading','Subjects')


@section('page_style')
<link rel="stylesheet" href="{{ asset('/css/treeview.css') }}" />
@endsection

@section('section')
<div class ="row">
	<div class ="col-sm-8">
		<form class="form-horizontal" action="{{ url('/'.Route::current()->uri()) }}" method="get">
			<div class="form-group">
				<div class="col-sm-12">
					<input type="text" class="form-control" name="search_text" placeholder="Search Subject..">
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
	<div class="col-sm-4">
		@include('widgets.panel_button',
            [   'pb_link'=> url("/home/subject/create"),
                'pb_icon'=>"fa fa-sitemap fa-5x",
                'pb_icon_ani'=>"animated shake",
                'pb_label'=>"Add a Subject",
				'pb_heading' => 124,
				'pb_sub_heading' => "Total Subjects!",
				'pb_fclass'=>"red",
                'pb_class'=>"panel-danger"       ])
	</div>
</div>
<div class ="row">
	<div class ="col-sm-8">
		<span class="label label-default">Search Result For "{{ isset($st)? $st : "" }}"</span>
	</div>
</div>

@if (isset($subject_data) )

<div class="row">
	<div class="col-md-12">
		@component('widgets.card', ['card_class'=>'bg-warning animated bounceInRight'])
		@slot("card_heading","Subjects")
		<div class=" table-responsive">
			<ul class="tree">
			@foreach($subject_data as $dept)
				<li><span > {{ $dept->name }}</span>
					<ul>
					@foreach($dept->batch_ids as $batch_id)
						<li> <span> {{ Utility::ordinal_suffix($batch_id->sem_no) }} Semester</span>
							<ul>
								@foreach($batch_id->subjects as $subject)
									<li>
										<span class="btn btn-info">
											<a href="{{ route('home.subject.update',[$subject->subject_code]) }}" title=edit >
												<i class="fa fa-cog fa-fw"></i>
											</a>
										</span>
										<span class="btn btn-danger">
											<a href="{{ route('home.subject.delete',[$subject->subject_code]) }}" title=delete>
												<i class="fa fa-minus fa-fw"></i>
											</a>
										</span>
										{{$subject->subject_code}} - <b>{{$subject->subject_name}}</b>
									</li>
								@endforeach
							</ul>
						</li>
					@endforeach
					</ul>
				</li>
			@endforeach
			</ul>
		</div>
		@endcomponent
	</div>
</div>
@endif
@endsection


@section('page_script')
<script src="{{ asset('/js/treeview.js') }}"></script>
<script>
$(function () {
	if('{{ isset($st)? $st : "" }}'.length <= 0)
    $('.tree li.parent_li ul li > span').click();
});
</script>
@endsection