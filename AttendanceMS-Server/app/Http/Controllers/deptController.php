<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Utility;
use App\Dept;

class deptController extends Controller
{

    public function index(Request $req)
    {
        $st = Utility::filter($req->input('search_text', null));
        $data_list = ['st' => $st];
        $data_list['depts'] = Dept::search_dept($st);
        return view('dept.index', $data_list);
    }

    public function create(Request $req)
    {
        return view('dept.create');
    }

    public function update(Request $req, $id)
    {
        $id = Utility::is_integer($id);
        if (!$id) return view('error', ['msg' => 'Department Dosn\'t exists.']);
        $a = Dept::get_from_id($id);
        if (!$a) return view('error', ['msg' => 'Department Dosn\'t exists.']);
        return view('dept.update', ['edit' => true, 'dept' => $a]);
    }

    public function delete(Request $req, $id)
    {
        $id = Utility::is_integer($id);
        if ($id == null) return view('error', ['msg' => 'Department Dosn\'t exists.']);
        $dept = Dept::get_from_id($id);
        return view('confrm', ['id' => $id, 'method' => 'post',
            'msg' => "You want to delete '$dept->dept_name' department ? \n All Subjects should be deleted first."]);
    }

    public function delete_submit(Request $req)
    {
        $id = Utility::is_integer($req->input('id', null));
        if ($id == null) return view('error', ['msg' => 'Department Dosn\'t exists.']);
        $ans = Utility::is_bool($req->input('answer', 0));
        //dd($ans);
        if ($ans) {
            DB::beginTransaction();
            try {
                Dept::delete_from_id($id);
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                return view('error', ['msg' => 'Deletion Error. ' . $e->getMessage()]);
            }
            DB::commit();
            return view('success', ['back' => '/home/dept']);
        }
        return redirect('/home/dept');
    }

    public function create_submit(Request $req)
    {
        $dn = strtoupper(Utility::filter($req->input('dept_name', null)));
        $dy = Utility::is_integer($req->input('dept_year', null));
        $dfn = ucwords(Utility::filter($req->input('dept_full_name', null)));
        if ($dn == null || $dy == null || $dfn == null) {
            return view('error', ['msg' => 'All Data Required.']);
        }
        DB::beginTransaction();
        try {
            Dept::insert_and_return_id($dn, $dfn, $dy);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Insertion Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/dept']);
    }

    public function update_submit(Request $req)
    {
        $dn = strtoupper(Utility::filter($req->input('dept_name', null)));
        $dy = Utility::is_integer($req->input('dept_year', null));
        $dfn = ucwords(Utility::filter($req->input('dept_full_name', null)));
        $id = Utility::is_integer($req->input('dept_id', null));
        if ($dn == null || $dy == null || $dfn == null || $id == null) {
            return view('error', ['msg' => 'Data Submit Error.']);
        }
        DB::beginTransaction();
        try {
            Dept::update_from_id($id, $dn, $dfn, $dy);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Updation Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/dept']);
    }

}