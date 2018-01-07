<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Cookie;
use App\User;
use App\Utility;

class HomeController extends Controller
{

    public function index(Request $req)
    {
        $user_id = Utility::get_loged_user();
        $data = User::get_subject_class_count_taken_by_user_id($user_id);
        $al = User::get_top_4_student_of_teacher($user_id);
        return view('home', ['data' => $data, 'al' => $al]);
    }

    public function settings(Request $req)
    {
        $user_id = Utility::get_loged_user();
        $data = User::get_user_by_id($user_id);
        //dd($data);
        return view('settings', ["data" => $data]);
    }

    public function settings_oas_index(Request $req)
    {
        return view('home_settings');
    }

    public function user_edit_submit(Request $req)
    {
        $user_id = Utility::get_loged_user();
        $name = Utility::filter($req->get('name'));
        $email = Utility::is_email($req->get('email'));
        if ($email == null) return view('error', ['msg' => 'Email Not Valid.']);
        $phn = Utility::filter($req->get('phn'));
        DB::beginTransaction();
        try {
            User::db_update($user_id, $name, $email, $phn);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Updation Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/settings']);
    }

    public function user_password_submit(Request $req)
    {
        $user_id = Utility::get_loged_user();
        $old_pwd = $req->get('current_pwd');
        $new_pwd = $req->get('new_pwd');
        $cnf_pwd = $req->get('cnfrm_pwd');
        $user = User::get_user_by_id($user_id);
        if (password_verify($old_pwd, $user->password)) {
            if ($new_pwd == $cnf_pwd) {
                //dd( Utility::get_remember_cookie()!= null);
                User::change_password($user_id, $new_pwd);
                if (Utility::get_remember_cookie() != null) {
                    Utility::forget_remember_cookie();
                    return view('success', ['back' => '/home/settings']);
                }
                return view('success', ['back' => '/home/settings']);
            }
            return view('error', ['msg' => "New password Did not match Confirm Password"]);
        }
        return view('error', ['msg' => "Old Password Incorrect"]);
    }
}
