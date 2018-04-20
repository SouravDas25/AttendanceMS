<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use App\Utility;

use Illuminate\Routing\Controller;

class signupController extends Controller
{

    public function index(Request $req)
    {
        $int = $req->session()->get('internal_256_1235', null);
        $req->session()->forget('internal_256_1235');
        if ($int != 145) {
            return view('errors.503', ['msg' => 'Url Not Allowed.']);
        }
        return view('signup');
    }

    public function store(Request $req)
    {
        $user_name = Utility::filter($req->get('username'));
        $email = Utility::is_email($req->get('email'));
        $phone = Utility::is_phone($req->get('contact'));
        $password = $req->get('password');
        $user_type = Utility::get_admin_user_type();

        if ($password != $req->get('cnf_password')) {
            return view('errors.503', ['msg' => 'Password Mismatch.']);
        }

        $e = User::get_user_by_email($email);

        if ($e != NULL) {
            return view('errors.503', ['msg' => 'Account Already Exists With the Current Email.']);
        }
        $password = password_hash($password, PASSWORD_DEFAULT);
        DB::beginTransaction();
        try {
            User::insert_by_signUP($user_name, $email, $phone, $password, $user_type);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Insertion Error.' . $e->getMessage()]);
        }
        DB::commit();
        return redirect('/')->with(['login_email' => $email, 'login_perror' => true]);;
    }

}