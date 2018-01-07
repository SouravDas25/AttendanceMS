<?php namespace App\Http\Controllers;

use App\Batch;
use App\DB_Class;
use App\Dept;
use App\MyCsv;
use App\Student;
use App\Subject;
use App\Utility;
use DB;
use Illuminate\Http\Request;
use Storage;

function batch_dept_json()
{
    $jsomn_var = [];
    $depts = Dept::get_all_dept();
    foreach ($depts as $dept) {
        $batches = Batch::get_all_active_batch_of_dept($dept->id);
        array_push($jsomn_var, ["dept_data" => $dept, "batches" => $batches]);
    }
    //dd($jsomn_var);
    return $jsomn_var;
}

class batchController extends Controller
{

    public function setting_index(Request $req)
    {
        $st = Utility::filter($req->input('search_text', null));
        $data_list = ['st' => $st];
        if (!$st) $st = "";
        try {
            $data_list['batches'] = Batch::search_batch($st);
        } catch (\Illuminate\Database\QueryException $e) {
            return view('error', ['msg' => 'Updation Error.' . $e->getMessage()]);
        }
        //dd($data_list);
        return view('batch.setting_index', $data_list);
    }

    public function index(Request $req)
    {
        return view('batch.index', ["data" => batch_dept_json()]);
    }

    public function student_view(Request $req, $bn, $code)
    {
        $bn = Utility::is_integer($bn);
        if ($bn == null) return view('error', ['msg' => "Batch Not Found."]);
        $code = Utility::filter($code);
        if ($code == "total") {
            $data_list['students'] = Batch::get_all_students_total_attendance_in_batch($bn);
            $data_list['total'] = true;
            $data_list['total_classes'] = Batch::get_total_classes_taken_in_batch($bn);
        } else {

            $data_list['students'] = Subject::get_all_students_total_attendance_in_subject($bn, $code);
            $data_list['total_classes'] = Subject::get_total_classes_taken_in_subject($bn, $code);
        }
        if (!$data_list['total_classes']) {
            $r = Student::get_all_students_in_batch($bn);
            foreach ($r as $item) {
                $item->subject_code = $code;
                $item->attn = 0;
            }
            $data_list['students'] = $r;
        }
        //dd($data_list);
        $data_list['subjects'] = Subject::get_all_subject_in_batch($bn);
        $data_list['subject_code'] = $code;
        $data_list['batch'] = Batch::get_batch($bn);
        //dd($data_list);
        return view('batch.student', $data_list);
    }

    public function student_longlistview(Request $req, $bn, $code)
    {
        $bn = Utility::is_integer($bn);
        if ($bn == null) return view('error', ['msg' => "Batch Not Found."]);
        $code = Utility::filter($code);
        if ($code == "total") return view('error', ['msg' => "Subject '$code' Not Found. Select a Subject."]);
        $data_list['students'] = Student::get_all_students_in_batch($bn);
        $data_list['db_classes'] = DB_Class::get_all_classes_of_subject_by_batch($code, $bn);
        $data_list['subject'] = Subject::get_subject_by_code($code);
        //dd($data_list);
        $attendance_sheet = [];
        foreach ($data_list['students'] as $std) {
            $column = [];
            foreach ($data_list['db_classes'] as $clas) {
                $column[$clas->active_day_id] = DB_Class::is_student_present_on($std->student_id, $clas->active_day_id);
            }
            $attendance_sheet[$std->student_id] = $column;
        }
        $data_list['attendances'] = $attendance_sheet;
        //dd($data_list);
        return view('batch.longlist', $data_list);
    }

    public function create(Request $req)
    {
        $arr = Dept::get_all_dept();
        return view('batch.create', ['arr' => $arr]);
    }

    public function update(Request $req, $id)
    {
        $id = Utility::is_integer($id);
        if ($id == null) return view('error', ['msg' => "Batch Not Found."]);
        $sb = Batch::get_batch($id);
        if (!$sb) return view('error', ['msg' => 'Batch Dosn\'t exists.']);
        $arr = Dept::get_all_dept();
        return view('batch.update', ['arr' => $arr, 'sb' => $sb]);
    }

    public function delete(Request $req, $id)
    {
        $id = Utility::is_integer($id);
        if ($id == null) return view('error', ['msg' => "Batch Not Found."]);
        $msg = 'All Student in this batch Will Also Be deleted.
		If any student has attendence records please delete those first or
		else batch cannot be deleted.';
        return view('confrm', ['id' => $id, 'msg' => $msg, 'method' => 'POST']);
    }

    public function create_submit(Request $req)
    {
        $bd = $req->input('bch_date', null);
        if ($bd == null) return view('error', ['msg' => 'Batch Starting Year Required.']);
        $bd = Utility::get_default_start_date($bd);
        $bd = Utility::is_date('Y-m-d', $bd);
        $bdep = Utility::is_integer($req->input('bch_dept', null));
        $nc = Utility::filter($req->input('name_col', null));
        $rc = Utility::filter($req->input('roll_col', null));
        //dd($bd);
        if ($bd == null || $bdep == null || $nc == null || $rc == null) return view('error', ['msg' => 'All Data Required.']);
        if (!$req->hasFile('csv_file') && !$req->file('csv_file')->isValid()) {
            return view('error', ['msg' => 'Not A Valid Csv File.']);
        }
        if ($req->file('csv_file')->getClientSize() > 2 * 1024 * 1024) {
            return view('error', ['msg' => 'File Size should Be less Than 2 MB.']);
        }
        $path = $req->file('csv_file');
        $req->file('csv_file')->move(storage_path() . '/app/temp', 'temp_batch.csv');
        try {
            $csv = MyCsv::csv_to_assocoative_array(storage_path() . '/app/temp/temp_batch.csv');
        } catch (\Exception $e) {
            return view('error', ['msg' => 'CSV Reading Error. ' . $e->getMessage()]);
        }
        if (count($csv) <= 0) {
            return view('error', ['msg' => 'Not A Valid Csv File.']);
        }
        Storage::delete('/temp/temp_batch.csv');
        //dd($csv);
        DB::beginTransaction();
        try {
            $batch_no = Batch::insert_batch($bd, $bdep);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Updation Error.' . $e->getMessage()]);
        }
        foreach ($csv as $item) {
            $std_name = Utility::filter($item[$nc]);
            $std_roll = Utility::is_integer($item[$rc]);
            if ($std_name == null || $std_roll == null) {
                DB::rollBack();
                return view('error', ['msg' => 'Not A Valid Student Name or Roll.']);
            }
            try {
                Student::insert_student($std_name, $std_roll, $batch_no);
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return view('error', ['msg' => 'Updation Error.' . $e->getMessage()]);
            }
        }
        DB::commit();
        return view('success', ['back' => '/home/batch/setting/index']);
    }

    public function update_submit(Request $req)
    {
        $bd = Utility::is_date('Y-m-d', $req->input('bch_date', null));
        $bdep = Utility::is_integer($req->input('bch_dept', null));
        $id = Utility::is_integer($req->input('id', null));
        if ($bd == null || $bdep == null || $id == null) {
            return view('error', ['msg' => 'All Data Required OR Incorrect Data.']);
        }
        DB::beginTransaction();
        try {
            Batch::update_batch($id, $bd, $bdep);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Updation Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/batch/setting/index']);
    }

    public function delete_submit(Request $req)
    {
        $uid = Utility::is_integer($req->input('id', null));
        $ans = Utility::is_bool($req->input('answer', 0));
        if ($ans) {
            DB::beginTransaction();
            try {
                Batch::delete_batch($uid);
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return view('error', ['msg' => 'Deletion Error.' . $e->getMessage()]);
            }
            DB::commit();
            return view('success', ['back' => '/home/batch/setting/index']);
        }
        return Redirect("/home/batch/setting/index");
    }

}