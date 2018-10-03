<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use Illuminate\Routing\Controller;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;

use App\Utility;

/**
 *  Login Controller 
 */
class loginController extends Controller
{
    /**
     * logout function
     */
    public function out(Request $req)
    {
        if (!Utility::is_loged_in()) {
            return Redirect("/");
        }
        User::logout_user();
        if (Utility::has_remember_cookie()) {
            Utility::forget_remember_cookie();
        }
        return Redirect('/');
    }

    public function in(Request $req)
    {
        if (User::is_loged_in()) {
            return Redirect("home");
        }

        $email = Utility::is_email($req->input('email', null));
        $password = $req->input('password', null);
        $remember = Utility::is_bool($req->input('remember', false));
        if ($email == null) return redirect('/')->with(['login_error' => true, 'login_perror' => true]);

        $e = User::get_user_by_email($email);
        if ($e == null) {
            return redirect('/')->with(['login_error' => true, 'login_perror' => true]);
        }
        //dd($e);

        if (!password_verify($password, $e->password)) {
            return redirect('/')->with(['login_email' => $email, 'login_perror' => true]);
        }
        if (User::login_user($e->user_id) == false) return redirect('/')->with(['login_email' => $email, 'login_perror' => true]);
        User::clear_remember_id($e->user_id);
        if ($remember) {
            $rem_id = User::insert_remember_id($e->user_id);
            Utility::set_remember_cookie($rem_id);
        }
        return redirect('home');
    }

    public function fp_index(Request $req)
    {
        if (User::is_loged_in()) {
            return Redirect("home");
        }
        return view('forgot_password.index');
    }

    public function fp_reset(Request $req)
    {
        if (User::is_loged_in()) {
            return Redirect("home");
        }
        $email = Utility::is_email($req->input('email', null));

        $e = User::get_user_by_email($email);
        if ($e == null) {
            return view('open_error', ['msg' => 'Email Address not registered.']);
        }
        $token = User::get_user_password_token($e->user_id);
        if ($token == null) {
            $token = User::insert_password_token($e->user_id);
        }
        $mail = new ResetPassword($token);
        Mail::to($email)->send($mail);
        return view('open_success', ['back' => "/", 'msg' => "Password Reset Link sent to <i>'$e->email'</i>."]);
    }

    public function fp_reset_token(Request $req, $token)
    {
        $user = User::get_user_by_password_token($token);
        if ($user == null) return view('open_error', ['msg' => 'Password reset token incorrect.']);
        return view('forgot_password.reset', ['token' => $token]);
    }

    public function fp_delete_token(Request $req, $token)
    {
        $user = User::get_user_by_password_token($token);
        if ($user == null) return view('open_error', ['back' => '/', 'msg' => 'Password reset token incorrect.']);
        User::delete_user_password_token($token);
        return view('open_success', ['back' => '/', 'msg' => 'Your Response Has Been Taken Into Account.']);
    }

    public function fp_reset_submit(Request $req)
    {
        $token = Utility::filter($req->input('pr_token', null));
        $new_pwd = $req->input('password', null);
        $cnf_pwd = $req->input('password_confirmation', null);
        $user = User::get_user_by_password_token($token);
        if ($user == null) return view('open_error', ['msg' => 'Password reset token incorrect.']);
        if ($new_pwd === $cnf_pwd) {
            //dd( Utility::get_remember_cookie()!= null);
            User::change_password($user->user_id, $new_pwd);
            User::delete_user_password_token($token);
            if (Utility::get_remember_cookie() != null) {
                Utility::forget_remember_cookie();
                return view('open_success', ['back' => '/', 'msg' => 'Password Changed.']);
            }
            return view('open_success', ['back' => '/', 'msg' => 'Password Changed.']);
        }
        return view('open_error', ['msg' => "New password Did not match Confirm Password"]);
    }


}