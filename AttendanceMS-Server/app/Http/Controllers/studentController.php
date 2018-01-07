<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Student;
use App\Dept;
use App\Utility;

class studentController extends Controller
{

    public function index(Request $req)
    {
        $st = Utility::filter($req->input('search_text', null));
        $data_list = ['st' => $st];
        $data_list['students'] = Student::search_student($st);
        return view('student.index', $data_list);
    }

    public function create(Request $req)
    {
        $arr = Dept::get_all_active_batches();
        return view('student.create', ['arr' => $arr]);
    }

    public function update(Request $req, $id)
    {
        $id = Utility::is_integer($id);
        if ($id == null) return view('error', ['msg' => 'Student Dosn\'t exists.']);
        $arr = Dept::get_all_active_batches();
        $std = Student::get_student_by_id($id);
        if (!$std) return view('error', ['msg' => 'Student Dosn\'t exists.']);
        return view('student.update', ['arr' => $arr, 'std' => $std]);
    }

    public function delete(Request $req, $id)
    {
        $id = Utility::is_integer($id);
        if ($id == null) return view('error', ['msg' => 'Student Dosn\'t exists.']);
        $std = Student::get_student_by_id($id);
        $msg = "You Want To Delete <b>$std->student_name</b> ?";
        return view('confrm', ['id' => $id, 'msg' => $msg, 'method' => 'post']);
    }

    public function create_submit(Request $req)
    {
        $sn = Utility::filter($req->input('stud_name', null));
        $sr = Utility::is_integer($req->input('stud_roll', null));
        $sb = Utility::is_integer($req->input('stud_batch', null));
        if ($sn == null || $sr == null || $sb == null) return view('error', ['msg' => 'All Data Required.']);
        DB::beginTransaction();
        try {
            Student::insert_student($sn, $sr, $sb);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Insertion Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/student']);
    }

    public function update_submit(Request $req)
    {
        $sn = Utility::filter($req->input('stud_name', null));
        $sr = Utility::is_integer($req->input('stud_roll', null));
        $sb = Utility::is_integer($req->input('stud_batch', null));
        $id = Utility::is_integer($req->input('id', null));
        if ($sn == null || $sr == null || $sb == null || $id == null) return view('error', ['msg' => 'All Data Required.']);
        DB::beginTransaction();
        try {
            Student::update_student($id, $sn, $sr, $sb);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Updation Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/student']);
    }

    public function delete_submit(Request $req)
    {
        $uid = Utility::is_integer($req->input('id', null));
        if ($uid == null) return view('error', ['msg' => 'Student Dosn\'t exists.']);
        $ans = Utility::is_bool($req->input('answer', 0));
        if ($ans) {
            DB::beginTransaction();
            try {
                Student::delete_student($uid);
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return view('error', ['msg' => 'Deletion Error.' . $e->getMessage()]);
            }
            DB::commit();
            return view('success', ['back' => '/home/student']);
        }
        return redirect('home/student');
    }

}