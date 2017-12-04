<?php namespace App\Http\Middleware;

use Closure;

use App\Utility;

class homemiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$user_name =  Utility::get_loged_user() ;

		if( $user_name == NULL)
		{
			return redirect('/')->with(['login_error'=>true,'login_perror'=>true]);
		}
		
		return $next($request);
	}

}
