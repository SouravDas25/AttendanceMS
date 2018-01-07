<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\DB_Class;
use App\Utility;
use App\User;

class myclassController extends Controller
{

    public function index(Request $req)
    {
        $st = Utility::filter($req->input('search_text', NULL));
        $user_id = Utility::get_loged_user();
        if ($st) {
            $data["MyClassess"] = DB_Class::search_class($user_id, $st);
        } else {
            $data["MyClassess"] = DB_Class::get_all_class_in_days($user_id, 32);
        }
        $data["st"] = $st;
        $data["class_cnt"] = DB_Class::get_no_classes_taken_by_teacher($user_id);
        $data["class_mark_cnt"] = DB_Class::get_no_classes_marked_by_teacher($user_id);
        $data["max_hc"] = DB_Class::get_max_head_count_by_teacher($user_id);
        return view("myclass.index", $data);
    }

    public function update(Request $req, $id)
    {
        $id = Utility::is_integer($id);
        if ($id == null) return view('error', ['msg' => 'Class Dosn\'t exists.']);
        $data = DB_Class::get_class_details_by_active_day_id($id);
        if ($data == null) return view('error', ['msg' => 'Class Dosn\'t exists.']);
        $current_user = Utility::get_loged_user();
        if ($data->teacher_id != $current_user) {
            if (User::compare_user($current_user, $data->teacher_id) != 1)
                return view('error', ['msg' => 'You do not hav permission to edit this.']);
        }
        $students = DB_Class::get_class_attendance_list_by_active_day_id($id, $data->batch_no);
        //dd(['data'=>$data,'students'=>$students]);
        return view('myclass.update', ['data' => $data, 'students' => $students]);
    }

    public function delete(Request $req, $id)
    {
        $id = Utility::is_integer($id);
        if ($id == null) return view('error', ['msg' => 'Class Dosn\'t exists.']);
        $data = DB_Class::get_class_details_by_active_day_id($id);
        if ($data == null) return view('error', ['msg' => 'Class Dosn\'t exists.']);
        $current_user = Utility::get_loged_user();
        if ($data->teacher_id != $current_user) {
            if (User::compare_user($current_user, $data->teacher_id) != 1)
                return view('error', ['msg' => 'You do not hav permission to delete this.']);
        }
        return view('confrm', ['id' => $id, 'method' => 'post',
            'msg' => "You want to delete a class ?"]);
    }

    public function delete_submit(Request $req)
    {
        $id = Utility::is_integer($req->input('id', null));
        if ($id == null) return view('error', ['msg' => 'Class Dosn\'t exists.']);
        $ans = Utility::is_bool($req->input('answer', 0));
        if ($ans) {
            DB::beginTransaction();
            try {
                DB_Class::mydelete($id);
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return view('error', ['msg' => 'Deletion Error. ' . $e->getMessage()]);
            }
        }
        DB::commit();
        return view('success', ['back' => '/home/myclass']);
    }

    public function update_submit(Request $request)
    {
        $bd = $request->input('attn_data', null);
        //dd($bd);
        $teacher = Utility::get_loged_user();
        if ($bd) {
            try {
                $bd = json_decode($bd);
                Utility::get_last_json_error();
            } catch (\Exception $e) {
                return view('error', ['msg' => "Json Error " . $e->getMessage()]);
            }
        } else return view('error', ['msg' => 'Error Data Not Received.']);

        try {
            Utility::validate_sanitize_attn_json($bd);
        } catch (\Exception $e) {
            return view('error', ['msg' => $e->getMessage()]);
        }

        $id = $bd->active_day_id;

        DB::beginTransaction();
        try {
            DB_Class::mydelete($id);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Deletion Error. ' . $e->getMessage()]);
        }
        try {
            DB_Class::mycreate($teacher, $bd, $bd->attn);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Deletion Error. ' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/myclass']);
    }

}