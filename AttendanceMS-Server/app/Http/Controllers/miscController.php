<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utility;
use DB;

class miscController extends Controller
{
    public static function index(Request $request)
    {
        $data = DB::select("SELECT * FROM misc WHERE config_id = 1;")[0];
        return view("misc.index", ['data' => $data]);
    }

    public static function update_submit(Request $request)
    {
        $sem_length = Utility::is_float($request->input('semLength', null));
        $semSD = Utility::is_integer($request->input('semSD', null));
        $semSM = Utility::is_integer($request->input('semSM', null));
        //dd($sem_length);
        DB::beginTransaction();
        try {
            $query = "UPDATE misc SET sem_starting_day = ? ,sem_starting_month = ?,
        sem_length = ? WHERE config_id = 1; ";
            DB::update($query, [$semSD, $semSM, $sem_length]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return view('error', ['msg' => 'Updation Error.' . $e->getMessage()]);
        }
        DB::commit();
        return view('success', ['back' => '/home/misc']);
    }

}
