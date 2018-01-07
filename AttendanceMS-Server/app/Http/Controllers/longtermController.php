<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utility;
use App\Dept;
use App\Student;
use App\Batch;
use App\Normalize;
use DB;

class longtermController extends Controller
{
    public static function index(Request $request)
    {
        $depts = Dept::get_all_dept();
        foreach ($depts as $dept) {
            $dept->batches = Dept::get_all_batch_of_dept($dept->id);
        }
        $data_list['data'] = $depts;
        return view('long_term.index', $data_list);
    }

    public static function view(Request $request, $bn)
    {
        $bn = Utility::is_integer($bn);
        $r = Student::get_all_students_in_batch($bn);
        foreach ($r as $student) {
            $o = Student::get_student_avg_overview_of_all_sems($student->student_id);
            $student->attn = $o->attn;
            $student->total = $o->total;
        }
        $data_list['students'] = $r;
        $data_list['batch'] = Batch::get_batch($bn);
        return view('long_term.student', $data_list);
    }

    public static function view_details(Request $request, $id)
    {
        $id = Utility::is_integer($id);
        $data_list['student'] = Student::get_student_by_id($id);
        $data_list['sem_result'] = Student::get_student_all_sem_overview($id);
        $data_list['batch'] = Batch::get_batch($data_list['student']->batch_no);
        return view('long_term.view_details', $data_list);
    }

    public static function create(Request $request, $id)
    {
        $id = Utility::is_integer($id);
        $data_list['student'] = Student::get_student_by_id($id);
        $data_list['sems'] = Utility::get_sem_no_till($data_list['student']->year);
        //dd($data_list);
        return view('long_term.create', $data_list);
    }

    public static function create_submit(Request $request)
    {
        $student_id = Utility::is_integer($request->input('std_id', null));
        $sem_no = Utility::is_integer($request->input('sem_no', null));
        $attn = Utility::is_integer($request->input('attn', null));
        $total = Utility::is_integer($request->input('total', null));
        if ($student_id == null || $sem_no == null || $attn == null || $total == null) {
            return view('error', ['msg' => 'All Data Required.']);
        }
        if ($attn > $total) {
            return view('error', ['msg' => 'Class Attended Cannot Be Greater Than Total Classes Taken.']);
        }
        DB::beginTransaction();
        try {
            Normalize::insert($student_id, $sem_no, $attn, $total);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Insertion Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/long.term/view/details/' . $student_id]);
    }

    public static function update(Request $request, $id, $sem_no)
    {
        $id = Utility::is_integer($id);
        $sem_no = Utility::is_integer($sem_no);
        if ($sem_no == null || $id == null) {
            return view('error', ['msg' => 'Invalid Data Recieved.']);
        }
        $data_list['student'] = Student::get_student_by_id($id);
        $data_list['sems'] = Utility::get_sem_no_till($data_list['student']->year);
        $data_list['data'] = Normalize::get_lt_student_record($id, $sem_no);
        if ($data_list['data'] == null || $data_list['student'] == null) {
            return view('error', ['msg' => 'Invalid Semester No.']);
        }
        if ($data_list['student'] == null) {
            return view('error', ['msg' => 'Invalid Student ID.']);
        }
        //dd($data_list);
        return view('long_term.update', $data_list);
    }

    public static function update_submit(Request $request)
    {
        $student_id = Utility::is_integer($request->input('std_id', null));
        $sem_no = Utility::is_integer($request->input('sem_no', null));
        $attn = Utility::is_integer($request->input('attn', null));
        $total = Utility::is_integer($request->input('total', null));
        if ($student_id == null || $sem_no == null || $attn == null || $total == null) {
            return view('error', ['msg' => 'All Data Required.']);
        }
        if ($attn > $total) {
            return view('error', ['msg' => 'Class Attended Cannot Be Greater Than Total Classes Taken.']);
        }
        DB::beginTransaction();
        try {
            Normalize::update($student_id, $sem_no, $attn, $total);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Updation Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/long.term/view/details/' . $student_id]);
    }

    public static function delete(Request $request, $id, $sem_no)
    {
        $id = Utility::is_integer($id);
        $sem_no = Utility::is_integer($sem_no);
        if ($id == null || $sem_no == null) return view('error', ['msg' => 'Error Attendance Record Dosn\'t exists.']);
        $student = Student::get_student_by_id($id);
        if ($student == null) return view('error', ['msg' => 'Invalid Student ID.']);
        return view('confrm', ['id' => $id, 'method' => 'post', 'no' => $sem_no, 'submit' => '/home/long.term/delete/submit',
            'msg' => "You Want To Delete $student->student_name  $sem_no Sems Record ?"]);
    }

    public static function delete_submit(Request $request)
    {
        $id = Utility::is_integer($request->input('id', null));
        $no = Utility::is_integer($request->input('no', null));
        if ($id == null || $no == null) return view('error', ['msg' => 'Attendance Record Dosn\'t exists.']);
        $ans = Utility::is_bool($request->input('answer', 0));
        //dd($ans);
        if ($ans) {
            DB::beginTransaction();
            try {
                Normalize::delete($id, $no);
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return view('error', ['msg' => 'Deletion Error. ' . $e->getMessage()]);
            }
            DB::commit();
            return view('success', ['back' => '/home/long.term/view/details/' . $id]);
        }
        return redirect('/home/long.term/view/details/' . $id);
    }

    public function normalize(Request $req)
    {
        return view('confrm', ['id' => "", 'method' => 'GET', 'submit' => "/home/long.term/normalize/submit",
            'msg' => "You want to normalize the database? All previous semesters attendance records will be sunk and stored as a single 
        overall attendance of the semester for each students ? "]);
    }

    public function normalize_submit(Request $req)
    {
        $ans = Utility::is_bool($req->input('answer', 0));
        if ($ans) {
            DB::beginTransaction();
            try {
                Normalize::sem_normalize();
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return view('error', ['back' => '/home/long.term', 'msg' => 'Database Normalization Failed. ' . $e->getMessage()]);
            }
            DB::commit();
            return view('success', ['back' => '/home/long.term', 'msg' => 'Database Normalized.']);
        }
        return redirect('/home/long.term');
    }
}
