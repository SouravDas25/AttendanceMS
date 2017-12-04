@extends('layouts.open')

@section('page_content')
<div class="container white" >
    <div class="section" >
        <div class="row">
            @component('widgets.card', ['card_class'=>'bg-warning animated bounceInRight','card_hclass'=>" cyan accent-4"]) 
            @slot("card_heading")
				<h5>Reset Password</h5>
            @endslot
                <form class="form-horizontal" role="form" method="POST" action="/password/reset/submit">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="pr_token" value="{{ $token }}">

					<div class="input-field">
						<input type="password" class="validate" name="password">
						<label>New Password</label>
					</div>

					<div class="input-field">
						<input type="password" class="validate" name="password_confirmation">
						<label>Confirm Password</label>
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
