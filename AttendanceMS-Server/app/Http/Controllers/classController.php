<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DB_Class as DB_Class;
use App\Dept;
use App\Batch;
use App\Subject;
use App\Student;
use DB;
use App\Utility as Utility;

function sub_dept_json()
{
    $jsomn_var = [];
    $depts = Dept::get_all_dept();
    foreach ($depts as $dept) {
        $batches = Batch::get_all_active_batch_of_dept($dept->id);
        $subvar = [];
        foreach ($batches as $batch) {
            $subjects = Subject::get_all_subject_in_batch($batch->batch_no);
            array_push($subvar, ["sem_no" => $batch->sem_no, "subjects" => $subjects]);
        }
        array_push($jsomn_var, ["dept_name" => $dept->name, "dept_fn" => $dept->full_name, "sems" => $subvar]);
    }
    //dd($jsomn_var);
    return $jsomn_var;
}

class classController extends Controller
{

    public function index(Request $req)
    {
        $jsin = sub_dept_json();
        //dd($jsin);
        return view('class.index', ['depts' => $jsin]);
    }

    public function subject(Request $req, $sub_code)
    {
        $sub_code = Utility::filter($sub_code);
        $batch = Subject::get_batch_by_subject_code($sub_code);
        if (!$batch) {
            return view('error', ['msg' => "Subject $sub_code Not Found."]);
        }
        $students = Student::get_all_students_in_batch($batch->batch_no);
        $sub_name = Subject::get_subject_by_code($sub_code)->name;
        //dd([$sub_code,$batch,$students]);
        return view('class.attendence', ["sub_code" => $sub_code, "sub_name" => $sub_name,
            "batch_no" => $batch->batch_no, 'students' => $students]);
    }

    public function subject_submit(Request $request)
    {
        $bd = $request->input('attn_data', null);
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

        $teacher = Utility::get_loged_user();
        if ($teacher == null) {
            view('error', ['msg' => 'Must Login to Take An attendence.']);
        }
        DB::beginTransaction();
        try {
            DB_Class::mycreate($teacher, $bd, $bd->attn);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Insertion Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/class', 'msg' => 'Attendance Saved. ']);
    }

}
