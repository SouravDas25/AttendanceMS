<div class="card bg-{{ isset($panel_class) ? $bg : 'warning' }} " >
	@if( isset($header)) 
	<div class="card-action {{{ isset($panel_hclass) ? $class : 'red' }}}">
		@yield ($as . '_panel_title')
	</div>
	@endif
	<div class="card-content">
		<span class="card-title "></span>
		@yield ($as . '_panel_body')
	</div>
</div>

