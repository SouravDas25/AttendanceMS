<div class="card {{ isset($card_class)? $card_class : "bg-warning" }}" 
	style="box-shadow: 1px 1px 7px #888888;box-sizing: border-box;">
	<div class="card-action {{ isset($card_hclass)? $card_hclass : "red" }}">
		{{ $card_heading }}
	</div>
	<div class="card-content">
		<span class="card-title "></span>
		{{ $slot }}
	</div>
	
</div>