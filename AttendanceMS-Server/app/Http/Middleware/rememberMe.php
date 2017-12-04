<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Utility;

class rememberMe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( !Utility::is_loged_in() )
        {
            $rem_id = Utility::get_remember_cookie();
            if($rem_id != null)
            {
                $user = User::get_user_by_rem_id($rem_id);
                if($user != null)
                {
                    User::login_user($user->user_id);
                }
            }
        }
        return $next($request);
    }
}
