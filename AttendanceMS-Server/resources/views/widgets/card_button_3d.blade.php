<a href="{{ isset($pb_link)? $pb_link : ''}}">
	<div class="card horizontal cardIcon waves-effect waves-dark"
	style="box-shadow: 1px 1px 7px #888888;box-sizing: border-box;">

		<div class="card-image {{ isset($pb_fclass)? $pb_fclass : 'default'}} zomify">
			<i class="{{ isset($pb_icon)? $pb_icon : ''}} {{ isset($pb_icon_ani)? $pb_icon_ani : ''}}"></i>
		</div>
		<div class="card-stacked {{ isset($pb_class)? $pb_class : 'default'}} ">
			<div class="card-content">
				<h3>
                    {{ isset($pb_heading)? $pb_heading : "" }} 
                </h3>
			</div>
			<div class="card-action">
				<strong>{{ isset($pb_label)? $pb_label : ''}} </strong>
                <span class="clearfix" ></span>
			</div>
		</div>

	</div>
</a>