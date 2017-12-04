<div class="card {{ isset($table_class)? $table_class : "" }}" style="box-shadow: 1px 1px 7px #888888;box-sizing: border-box;">
	<div class="card-action {{ isset($table_hclass)? $table_hclass : "red" }}">
		{{ $table_heading }}
	</div>
	<div class="card-content bg-warning">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover"
			id="{{ isset($table_id)? $table_id : "dataTables-example" }}">
				{{ $slot }}
			</table>
		</div>
	</div>
</div>
