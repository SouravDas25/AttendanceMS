<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DB_Class;
use DB;
use App\User;
use App\Dept;
use App\Batch;
use App\Subject;
use App\Student;
use App\Utility;

class appController extends Controller
{
    public function app_in(Request $req)
    {
        $email = Utility::is_email($req->input('email', null));
        $password = $req->input('password', null);
        $data['valid'] = false;

        if ($email == null) {
            $data['email_error'] = true;
            return json_encode($data);
        }

        $e = User::get_user_by_email($email);
        if ($e == null) {
            $data['email_error'] = true;
            return json_encode($data);
        }
        //dd($e);tict.edu.attendence2005

        if (!password_verify($password, $e->password)) {
            $data['password_error'] = true;
            return json_encode($data);
        }
        $data['valid'] = true;
        $data['user_name'] = $e->user_name;
        $data['user_id'] = $e->user_id;
        return json_encode($data);
    }

    public function app_login(Request $req, $email, $password)
    {
        if (User::is_loged_in()) {
            return Redirect("home");
        }

        $email = Utility::is_email($email);

        if ($email == null) return "Email NUll";

        $e = User::get_user_by_email($email);
        if ($e == null) {
            return "Email Incorrect";
        }
        //dd($e);

        if (!password_verify($password, $e->password)) {
            return "Password Incorrect";
        }
        if (User::login_user($e->user_id) == false) return "Email Incorrect";
        return redirect('home');
    }

    public function app_sync(Request $req)
    {
        $data['dept'] = DB::select('SELECT * FROM dept');
        $data['batch'] = DB::select('SELECT * FROM batch');
        $data['subject'] = DB::select('SELECT * FROM subject');
        $data['student'] = DB::select('SELECT * FROM student');
        $data['student_batch'] = DB::select('SELECT * FROM student_batch');
        return json_encode($data);
    }

    public function app_submit(Request $request)
    {
        $bd = $request->input('attn_data', null);
        $response['valid'] = false;
        if ($bd) {
            try {
                $bd = json_decode($bd);
                Utility::get_last_json_error();
            } catch (\Exception $e) {
                $response['err_des'] = $e->getMessage();
                return json_encode($response);
            }
        } else {
            $response['err_des'] = 'Error Data Not Received.';
            return json_encode($response);
        }

        try {
            Utility::validate_sanitize_attn_json($bd);
        } catch (\Exception $e) {
            $response['err_des'] = $e->getMessage();
            return json_encode($response);
        }


        if (!property_exists($bd, 'teacher_id')) {
            $response['err_des'] = 'Teacher ID not present.';
            return json_encode($response);
        }
        $teacher = $bd->teacher_id;
        if ($teacher == null) {
            $response['err_des'] = 'Must Login to Take An attendence.';
            return json_encode($response);
        }
        DB::beginTransaction();
        try {
            DB_Class::mycreate($teacher, $bd, $bd->attn);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $response['err_des'] = $e->getMessage();
            return json_encode($response);
        }
        DB::commit();
        $response['valid'] = true;
        return json_encode($response);
    }


}
