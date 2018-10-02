@extends('layouts.dashboard')
@section('page_heading','Miscellaneous Settings')

@section('page_style')
<link rel="stylesheet" href="{{ asset('/css/bootstrap-slider.min.css') }}" />
<style>
#ex1Slider .slider-selection {
	background: #BABABA;
}

</style>
@endsection

@section('section')

<div class="row animated bounceInLeft">
	<div class="col-md-12"  >
		@component('widgets.card', ['card_class'=>' '])
		@slot("card_heading","Semester Details")
			<form class="form-horizontal" action='/home/misc/update/submit' method="post">
				<fieldset>

				<div class="form-group">
				  	<div class="col-md-2 col-md-offset-2" id="semno">
				  		No Of Semester : 6
				  	</div>
				</div>

				<!-- Text input-->
				<div class="form-group">
					<label class="col-md-1 control-label" id="sem-str_D">
				  		
				  	</label>
					<div class="col-md-10">
						<div class="progress" style="min-height:60px;" id="progress-bar">

						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-4 control-label" id="sem-length">
				  		Semester Length ( in days )
				  	</label>
				  	<div class="col-md-4 range-field">
						<input name="semLength" id="semLength" type="range" min="156" max="187" step="0.5" required>
				  	</div>
				</div>

				<div class="form-group">
					<label  class="col-md-4 control-label" >Semester Start Day</label>
				  	<div class="col-md-4" id="sem-sd">
				  		<input id="semSD" name="semSD" type="number" class="form-control input-md" 
						  value="{{ $data->sem_starting_day  }}" min="1" max="31">
				  	</div>
				</div>

				<div class="form-group">
					<label  class="col-md-4 control-label" >Semester Start Month</label>
				  	<div class="col-md-4" id="sem-sm">
					  <input name="semSM" id="semSM" type="number" class="form-control input-md" 
					  value="{{ $data->sem_starting_month  }}" min="1" max="12">
				  	</div>
				</div>

				<div class="form-group">
					<label  class="col-md-4 control-label" >Total Years in Course</label>
				  	<div class="col-md-4" id="sem-sm">
					  <input name="cys" id="cys" type="number" class="form-control input-md" 
					  value="3" min="1" max="6">
				  	</div>
				</div>

				@if( Utility::is_user_admin() )
				<div class="form-group">
				  <label class="col-md-4 control-label" for=""></label>
				  <div class="col-md-4">
				    <button id="" name="" class="btn btn-primary">Submit</button>
				  </div>
				</div>
				
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				@endif

				</fieldset>
			</form>
		@endcomponent
	</div>
</div>

@endsection

@section('page_script')
<script>

var slid = $('#semLength');
var progress_bar =$("#progress-bar");

var col_list = ['danger','success','info','warning','primary'];

function right_elememt(msg){ 
	return "<br><span class='' style='padding-right:2px;' >"+msg+"</span>";
}

function mon_day(d){
	var options = { year: 'numeric', month: 'short', day: '2-digit'};
	var _resultDate = new Intl.DateTimeFormat('en-GB', options).format(d);
	return _resultDate;
}

function add_progess_stack(i,d){
	var rand = col_list[i%col_list.length];
	var dom = "<div class='progress-bar Megatron progress-bar-" + 
			rand +"' style='width: "+ each_div_cnt +"%'> <h5>" + ordinal_suffix_of(i+1) + right_elememt(mon_day(d)) +
			"</h5><span class=\"sr-only\">"+each_div_cnt+"% Complete (success)</span></div>";
	progress_bar.append(dom);
	d.setDate(d.getDate() + slide_val);
}

function slideChange(){
	var max_years = $('#cys').val();
	slide_val = parseFloat(slid.val());
	each_year_div  = round(365.25 / slide_val,1);
	if(each_year_div<1) each_year_div=1;
	total_div = Math.floor( max_years * each_year_div);
	each_div_cnt = 100/total_div;
	progress_bar.html("");
	str_m = parseInt($('#semSM').val());
	str_d = parseInt($('#semSD').val());
	var d = new Date();
	d.setDate(str_d);
	d.setMonth(str_m-1);
	$('#sem-str_D').html(mon_day(d));
	d.setDate(d.getDate() + slide_val);
	for(i=0;i<=total_div;i++)
	{
		add_progess_stack(i,d);
	}
	$('#semno').html("No Of Semester : " + total_div);
}


$(document).ready(function(){
	slid.change(slideChange);
	$('#semSM').change(slideChange);
	$('#semSD').change(slideChange);
	$('#cys').change(slideChange);
	slid.val( {{ $data->sem_length  }} );
	slideChange();
});
 </script>
@endsection