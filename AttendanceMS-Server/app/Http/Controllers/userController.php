<?php namespace App\Http\Controllers;

use App\Dept;
use App\Mail\RegisterUser;
use App\User;
use App\Utility;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class userController extends Controller
{

    public function index(Request $req)
    {
        $st = Utility::filter($req->input('search_text', null));
        $data_list = ['st' => $st];
        $data_list['users'] = User::search_user($st);
        return view('user.index', $data_list);
    }

    public function create(Request $req)
    {
        return view('user.create');
    }

    public function update(Request $req, $id)
    {
        $id = Utility::is_integer($id);
        if ($id == null) return view('error', ['msg' => 'User Dosn\'t exists.']);
        $current_user = Utility::get_loged_user();
        if (User::compare_user($current_user, $id) != 1) {
            return view('error', ['msg' => 'Dosn\'t have permission to update user.']);
        }
        $a = User::get_user_by_id($id);
        if ($a == null) return view('error', ['msg' => 'User Dosn\'t exists.']);
        $arr = Dept::get_all_dept();
        return view('user.update', ['id' => $id, 'all' => $a, 'arr' => $arr,
            'all_types' => Utility::get_all_user_type()]);
    }

    public function delete(Request $req, $id)
    {
        $id = Utility::is_integer($id);
        if ($id == null) return view('error', ['msg' => 'User Dosn\'t exists.']);
        $current_user = Utility::get_loged_user();
        if (User::compare_user($current_user, $id) != 1) {
            return view('error', ['msg' => 'Dosn\'t have permission to delete user.']);
        }
        $a = User::get_user_by_id($id);
        if ($a == null) return view('error', ['msg' => 'User Dosn\'t exists.']);
        $msg = "You Want To Delete <b>$a->user_name</b> ?";
        return view('confrm', ['id' => $id, 'msg' => $msg]);
    }

    public function create_submit(Request $req)
    {
        $un = Utility::filter($req->input('user_name', null));
        $ue = Utility::is_email($req->input('user_email', null));
        $up = str_random(10);
        $pwd = $up;
        if ($un == null) return view('error', ['msg' => 'Username Not Valid.']);
        if ($ue == null) return view('error', ['msg' => 'Eamil Not Valid.']);
        if (strlen($pwd) < 10) return view('error', ['msg' => 'Internal Error Try Again. Code = ' . $pwd]);
        $up = password_hash($up, PASSWORD_DEFAULT);
        $usr = User::get_user_by_email($ue);
        if ($usr != null) return view('error', ['msg' => "User with E-Mail Address $ue already exsists."]);
        DB::beginTransaction();
        try {
            $user_id = User::db_insert($un, $ue, $up);
            $token = User::insert_password_token($user_id);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => $e->getMessage()]);
        }
        DB::commit();
        $mail = new RegisterUser($un, $ue, $token, Utility::get_user_email(), Utility::get_user_name());
        Mail::to($ue)->send($mail);
        return view('success', ['back' => '/home/faculty']);
    }


    public function update_submit(Request $req)
    {
        $user_type = Utility::filter($req->input('user_type', null));
        $uid = Utility::is_integer($req->input('user_id', null));
        $dept_id = Utility::is_integer($req->input('dept', null));
        if ($uid == null && ($dept_id == null || $user_type == null)) return view('error', ['msg' => 'All Data Required To Update.']);
        $current_user = Utility::get_loged_user();
        if (User::compare_user($current_user, $uid) != 1) {
            return view('error', ['msg' => 'Dosn\'t have permission to update user.']);
        }
        DB::beginTransaction();
        try {
            if (User::update_user_by_admin($uid, $user_type, $dept_id) == false) return view('error', ['msg' => 'Updation Error.']);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Updation Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/faculty']);
    }

    public function delete_submit(Request $req)
    {
        $uid = Utility::is_integer($req->input('id', null));
        if ($uid == null) return view('error', ['msg' => 'User Dosn\'t exists.']);
        $ans = Utility::is_bool($req->input('answer', 0));
        $current_user = Utility::get_loged_user();
        if (User::compare_user($current_user, $uid) != 1) {
            return view('error', ['msg' => 'Dosn\'t have permission to delete user.']);
        }
        if ($ans) {
            DB::beginTransaction();
            try {
                if (User::db_delete($uid) == false) return view('error', ['msg' => 'Updation Error.']);
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return view('error', ['msg' => 'Updation Error.' . $e->getMessage()]);
            }
            DB::commit();
            return view('success', ['back' => '/home/faculty']);
        }
        return Redirect("/home/faculty");
    }

}
