<?php

namespace App;

use DB;
use Cookie;
use App\User;

class Utility
{
    public static function filter($var)
    {
        $var = trim($var);
        $var = filter_var($var, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        return $var;
    }

    public static function is_email($var)
    {
        $var = Utility::filter($var);
        $var = filter_var($var, FILTER_SANITIZE_EMAIL);
        if (!filter_var($var, FILTER_VALIDATE_EMAIL) === false) {
            return $var;
        }
        return null;
    }

    public static function is_integer($var)
    {
        $var = Utility::filter($var);
        $var = filter_var($var, FILTER_SANITIZE_NUMBER_INT);
        if (filter_var($var, FILTER_VALIDATE_INT) === 0 || !filter_var($var, FILTER_VALIDATE_INT) === false) {
            return $var;
        }
        if (is_numeric($var)) return $var;
        return null;
    }

    public static function is_bool($var)
    {
        $var = Utility::filter($var);
        return filter_var($var, FILTER_VALIDATE_BOOLEAN);
    }

    public static function is_float($var)
    {
        $var = Utility::filter($var);
        $var = filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        return filter_var($var, FILTER_VALIDATE_FLOAT);
    }

    public static function is_date($format, $var)
    {
        $var = Utility::filter($var);
        $date = date_parse_from_format($format, $var);
        if (checkdate($date['month'], $date['day'], $date['year']) == TRUE) {
            return $var;
        }
        return null;
    }


    public static function is_phone($var)
    {
        $phone = preg_replace('/[^0-9]/', '', $var);
        if (strlen($phone) === 10) {
            return $phone;
        }
        return null;
    }

    public static function get_sem_no_till($year)
    {
        $sems = [];
        for ($i = 1; $i <= $year * 2; $i++) {
            array_push($sems, $i);
        }
        return $sems;
    }

    public static function percentage($a, $b)
    {
        if ($b == 0) return 0;
        return round($a / $b * 100, 2);
    }

    public static function ordinal_suffix($int)
    {
        return $int . date("S", mktime(0, 0, 0, 0, $int, 0));
    }

    public static function get_sem_start_month($current_month)
    {
        if ($current_month < 7 && $current_month >= 1) return 1;
        return 7;
    }

    public static function get_loged_user()
    {
        return session()->get('user_id', null);
    }

    public static function is_loged_in()
    {
        $user_id = session()->get('user_id', null);
        if ($user_id == null) return false;
        return true;
    }

    public static function get_user_name()
    {
        $user_id = session()->get('user_id', null);
        if ($user_id == null) return false;
        return User::get_name($user_id);
    }

    public static function get_user_email()
    {
        $user_id = session()->get('user_id', null);
        if ($user_id == null) return false;
        return User::get_email($user_id);
    }

    public static function is_user_admin()
    {
        $user_id = session()->get('user_id', null);
        if ($user_id == null) return false;
        return User::is_admin($user_id);
    }

    public static function is_user_super_admin()
    {
        $user_id = session()->get('user_id', null);
        if ($user_id == null) return false;
        return User::is_admin($user_id);
    }

    public static function get_remember_cookie_name()
    {
        return 'OAS_user_rem';
    }

    public static function set_remember_cookie($rem_id)
    {
        Cookie::queue(cookie()->make(Utility::get_remember_cookie_name(), $rem_id, 525600));
    }

    public static function get_remember_cookie()
    {
        return Cookie::get(Utility::get_remember_cookie_name(), null);
    }

    public static function has_remember_cookie()
    {
        return Cookie::get(Utility::get_remember_cookie_name(), null) != null;
    }

    public static function forget_remember_cookie()
    {
        $c = cookie()->forget(Utility::get_remember_cookie_name());
        Cookie::queue($c);
    }

    public static function get_admin_user_type()
    {
        return User::get_admin_user_type();
    }

    public static function get_new_user_type()
    {
        return User::get_new_user_type();
    }

    public static function get_all_user_type()
    {
        return User::get_all_user_type(Utility::get_loged_user());
    }

    public static function validate_sanitize_attn_json($data)
    {
        if (property_exists($data, 'date')) {
            $data->date = Utility::is_date('Y-m-d', $data->date);
            $sem_month = Utility::get_sem_start_month(date('m'));
            $date_got = date_parse_from_format("Y-m-d", $data->date);
            if ($data->date == null) {
                throw new \Exception("Attendance Date Incorrect.");
            }
            if ($date_got['month'] < $sem_month || $date_got['year'] != date('Y')) {
                throw new \Exception("Attendance Date Incorrect.");
            }
        } else throw new \Exception("Attendance Date Not Found.");
        if (property_exists($data, 'code')) {
            $data->code = Utility::filter($data->code);
        } else throw new \Exception("Subject Code Not Found.");
        if (property_exists($data, 'topic')) {
            $data->topic = Utility::filter($data->topic);
        } else throw new \Exception("Attendance Topic Not Found");
        if (property_exists($data, 'remarks')) {
            $data->remarks = Utility::filter($data->remarks);
        } else throw new \Exception("Attendance Remarks Not Found.");
        if (property_exists($data, 'batch_no')) {
            $data->batch_no = Utility::is_integer($data->batch_no);
            if ($data->batch_no == null) {
                throw new \Exception("Batch Incorrect.");
            }
        } else throw new \Exception("Batch Not Found.");
        if (property_exists($data, 'attn')) {
            foreach ($data->attn as $attendance) {
                $attendance[0] = Utility::is_integer($attendance[0]);
                if ($attendance[0] == null) throw Exception("Attendance Data Incorrect");
                $attendance[0] = Utility::is_bool($attendance[0]);
            }
        } else throw new \Exception("Attendance Data Not Found.");
        return true;
    }

    public static function get_last_json_error()
    {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return true;
                break;
            case JSON_ERROR_DEPTH:
                throw new \Exception(' - Maximum stack depth exceeded');
                break;
            case JSON_ERROR_STATE_MISMATCH:
                throw new \Exception(' - Underflow or the modes mismatch');
                break;
            case JSON_ERROR_CTRL_CHAR:
                throw new \Exception(' - Unexpected control character found');
                break;
            case JSON_ERROR_SYNTAX:
                throw new \Exception(' - Syntax error, malformed JSON');
                break;
            case JSON_ERROR_UTF8:
                throw new \Exception(' - Malformed UTF-8 characters, possibly incorrectly encoded');
                break;
            case JSON_ERROR_RECURSION:
                throw new \Exception(' - One or more recursive references in the value to be encoded');
                break;
            case JSON_ERROR_INF_OR_NAN:
                throw new \Exception(' - One or more NAN or INF values in the value to be encoded');
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                throw new \Exception(' - A value of a type that cannot be encoded was given');
                break;
            case JSON_ERROR_INVALID_PROPERTY_NAME:
                throw new \Exception(' - A property name that cannot be encoded was given');
                break;
            case JSON_ERROR_UTF16:
                throw new \Exception(' - Malformed UTF-16 characters, possibly incorrectly encoded');
                break;
            default:
                throw new \Exception(' - Unknown error');
                break;
        }
        return true;
    }

    public static function rand_date($start = null, $end = null)
    {
        $start = ($start == null) ? 1 : strtotime($start);
        $end = ($end == null) ? time() : strtotime($end);
        $timestamp = mt_rand($start, $end);
        return date("Y-m-d", $timestamp);
    }

    public static function get_default_start_day()
    {
        $data = DB::select("SELECT sem_starting_day AS sd FROM misc WHERE config_id = 1;");
        return str_pad($data[0]->sd, 2, "0", STR_PAD_LEFT);
    }

    public static function get_default_start_month()
    {
        $data = DB::select("SELECT sem_starting_month AS sm FROM misc WHERE config_id = 1;");
        return str_pad($data[0]->sm, 2, "0", STR_PAD_LEFT);
    }

    public static function get_default_start_date($year)
    {
        return $year . "-" . Utility::get_default_start_month() . "-" . Utility::get_default_start_day();
    }

    public static function rootUrl($url = "")
    {
        return url($url);
    }

}


