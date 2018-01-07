<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Utility;
use App\Dept;
use App\Student;
use App\Subject;
use App\Batch;
use App\User;

class openSearchController extends Controller
{

    public function index(Request $request)
    {
        if (User::get_user_count() <= 0) {
            $request->session()->put('internal_256_1235', 145);
            return redirect('/signup');
        }
        $dept = Utility::is_integer($request->input('dept', null));
        $bn = Utility::is_integer($request->input('year', null));
        $roll = Utility::is_integer($request->input('roll', null));
        $data = [];
        $data['depts'] = Dept::get_all_dept();
        if ($dept != null && $bn != null && $roll != null) {
            $student = Student::get_student($roll, $bn);
            if (!$student) {
                $data['err_msg'] = "Roll No $roll Not Found.";
                return view('welcome', $data);
            }
            $student_id = $student->student_id;
            $data['batch'] = Batch::get_batch($bn);
            $result = Student::get_student_overview_in_sem($student_id, $data['batch']->sem_no);
            $data['sem_result'] = Student::get_student_all_sem_overview($student_id);
            $data['result'] = $result;
            $data['student'] = $student;
            $mon = [];
            for ($i = Utility::get_sem_start_month(date("m")); $i <= date("m"); $i++) {
                $total = Student::get_total_class_taken_in_month($i, $bn);
                $stud_attn = Student::get_total_class_of_student_in_month($student_id, $i);
                array_push($mon, ['total' => $total, 'attn' => $stud_attn, 'mon' => date("F", mktime(0, 0, 0, $i, 10))]);
            }
            $data['month'] = $mon;
            //dd($mon);
        }
        return view('welcome', $data);
    }

    public function get_year(Request $request)
    {
        $id = $request->input('dept', null);
        if ($id) {
            $arr = Dept::get_all_batch_of_dept($id);
            return json_encode($arr);
        }
        return json_encode([]);
    }
}
