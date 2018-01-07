<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Subject;
use App\Dept;
use App\Batch;
use App\Utility;

class subjectController extends Controller
{

    public function index(Request $req)
    {
        $st = Utility::filter($req->input('search_text', null));
        $data_list = ['st' => $st];
        $depts = Dept::get_all_dept();
        foreach ($depts as $dept) {
            $dept->batch_ids = Dept::get_all_batch_id_in_dept($dept->id);
            foreach ($dept->batch_ids as $batch_id) {
                $batch_id->subjects = Subject::search_subject_with_batch_id($st, $batch_id->batch_id);
            }
        }
        $data_list['subject_data'] = $depts;
        return view('subject.index', $data_list);
    }

    public function create(Request $req)
    {
        $arr = Batch::get_all_batches();
        return view('subject.create', ['arr' => $arr]);
    }

    public function update(Request $req, $code)
    {
        $code = Utility::filter($code);
        $sub = Subject::get_subject_by_code($code);
        if ($sub == null) return view('error', ['msg' => 'Subject Dosn\'t exists.']);
        $arr = Batch::get_all_batches();
        return view('subject.update', ['arr' => $arr, 'sub' => $sub]);
    }

    public function delete(Request $req, $code)
    {
        $code = Utility::filter($code);
        $sub = Subject::get_subject_by_code($code);
        if ($sub == null) return view('error', ['msg' => 'Subject Dosn\'t exists.']);
        $msg = "You Want To Delete <b>$sub->name($sub->code)</b> ? All attendance associated With this Subject must be deleted first.";
        return view('confrm', ['id' => $code, 'msg' => $msg, 'method' => 'post']);
    }

    public function create_submit(Request $req)
    {
        $sn = ucwords(Utility::filter($req->input('sub_name', null)));
        $sc = strtoupper(Utility::filter($req->input('sub_code', null)));
        $sd = Utility::is_integer($req->input('sub_batch', null));
        if ($sn == null) return view('error', ['msg' => 'Subject Name Should Be Specified.']);
        if ($sc == null) return view('error', ['msg' => 'Subject code Should Be Specified.']);
        if ($sd == null) return view('error', ['msg' => 'Subject Department-Semester Should Be Specified.']);
        if (substr_count($sc, "/") > 0) return view('error', ['msg' => 'Subject Code Should not Contain \'/\'.']);
        DB::beginTransaction();
        try {
            Subject::insert_and_return_code($sn, $sc, $sd);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Insertion Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/subject']);
    }

    public function update_submit(Request $req)
    {
        $sn = ucwords(Utility::filter($req->input('sub_name', null)));
        $sc = strtoupper(Utility::filter($req->input('sub_code', null)));
        $sd = Utility::is_integer($req->input('sub_batch', null));
        if ($sn == null) return view('error', ['msg' => 'Subject Name Should Be Specified.']);
        if ($sc == null) return view('error', ['msg' => 'Subject code Should Be Specified.']);
        if ($sd == null) return view('error', ['msg' => 'Subject Department-Semester Should Be Specified.']);
        if (substr_count($sc, "/") > 0 || substr_count($sc, "\\") > 0) return view('error', ['msg' => 'Subject Code Should not Contain \'/\'.']);
        DB::beginTransaction();
        try {
            Subject::update_from_code($sn, $sc, $sd);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Updation Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/subject']);
    }

    public function delete_submit(Request $req)
    {
        $uid = Utility::filter($req->input('id', null));
        //dd($uid);
        if ($uid == null) return view('error', ['msg' => 'Subject Dosn\'t exists.']);
        $ans = Utility::is_bool($req->input('answer', 0));
        if ($ans) {
            DB::beginTransaction();
            try {
                Subject::delete_from_code($uid);
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return view('error', ['msg' => 'Deletion Error.' . $e->getMessage()]);
            }
            DB::commit();
            return view('success', ['back' => '/home/subject']);
        }
        return Redirect("/home/subject");
    }
}