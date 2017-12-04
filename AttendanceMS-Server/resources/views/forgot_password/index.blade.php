@extends('layouts.open')

@section('page_content')
<div class="container white" >
    <div class="section" >
        <div class="row">
            @component('widgets.card', ['card_class'=>'bg-warning animated bounceInRight','card_hclass'=>" cyan accent-4"]) 
            @slot("card_heading")
                <h5 class="panel-heading">Reset Password !</h5>
            @endslot
                <form role="form" method="POST" action="/password/reset">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<div class="input-field">
							<input type="email" class="validate" name="email" value="{{ old('email') }}">
							<label class="col-md-4 control-label">E-Mail Address</label>
					</div>

					<div class="input-field">
						<button type="submit" class="btn btn-primary">
							Reset Password
						</button>
					</div>
				</form>
            @endcomponent
        </div>
    </div>
</div>
@endsection
