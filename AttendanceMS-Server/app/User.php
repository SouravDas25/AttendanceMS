<?php namespace App;

use DB;

class User
{

    public static function insert_by_signUP($user_name, $email, $phone, $password, $user_type)
    {
        DB::insert("INSERT into users (name,email,password,phn_no,user_type) values (?,?,?,?,?)",
            [$user_name, $email, $password, $phone, $user_type]);
        return DB::select('SELECT LAST_INSERT_ID() AS id;')[0]->id;
    }

    public static function db_insert($un, $ue, $up)
    {
        DB::insert("INSERT into users (name,email,password,phn_no,user_type) values (?,?,?,NULL,'normal')", [$un, $ue, $up]);
        return DB::select('SELECT LAST_INSERT_ID() AS id;')[0]->id;
    }

    public static function db_update($user_id, $name, $email, $phn)
    {
        $query = "UPDATE users SET name = ? , phn_no = ? , email = ? WHERE id = ?";
        DB::update($query, [$name, $phn, $email, $user_id]);
        return true;
    }

    public static function update_user_by_admin($user_id, $user_type, $user_dept)
    {
        if ($user_dept == null) {
            DB::update("UPDATE users SET user_type = ? WHERE id = ?", [$user_type, $user_id]);
        } else if ($user_type == null) {
            DB::update("UPDATE users SET dept_id = ? WHERE id = ?", [$user_dept, $user_id]);
        } else {
            DB::update("UPDATE users SET user_type = ? , dept_id = ?  WHERE id = ?", [$user_type, $user_dept, $user_id]);
        }
        return true;
    }


    public static function db_delete($id)
    {
        if ($id == null) return false;
        DB::delete("DELETE FROM reset_password WHERE user_id = ? ;", [$id]);
        DB::delete('DELETE FROM users WHERE id = ? ', [$id]);
        return true;
    }

    public static function get_user_by_email($email)
    {
        $e = DB::select('SELECT * from user_view where email = ?', [$email]);
        if (count($e) < 1 || $e == null) {
            return null;
        }
        return $e[0];
    }

    public static function get_user_by_id($id)
    {
        $e = DB::select('SELECT * from user_view where user_id = ?', [$id]);
        if (count($e) < 1 || $e == null) {
            return null;
        }
        return $e[0];
    }

    public static function search_user($search_text)
    {
        $search_text = "%$search_text%";
        $query = "SELECT * FROM user_view WHERE user_id LIKE ? OR
		user_name LIKE ? OR
		dept_name LIKE ? OR
		email LIKE ? OR
		phn_no LIKE ? OR 
		user_type LIKE ? ORDER BY user_name;";
        return DB::select($query, [$search_text, $search_text, $search_text, $search_text, $search_text, $search_text]);
    }

    public static function get_subject_class_count_taken_by_user_id($user_id)
    {
        $query = "SELECT COUNT(active_day_id) AS subcnt , subject_code ,  subject_name  
		FROM active_day_view_1
		WHERE teacher_id = ? AND DATEDIFF(CURRENT_DATE,class_date) < (182.625 +1)
		GROUP BY teacher_id , subject_code ";
        return DB::select($query, [$user_id]);
    }

    public static function change_password($user_id, $new_pwd)
    {
        $new_pwd = password_hash($new_pwd, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ?,remember_token = NULL WHERE id = ? ";
        DB::update($query, [$new_pwd, $user_id]);
    }

    public static function get_all_user()
    {
        $query = "SELECT
			user_id,
			password,
			remember_token,
			user_name,
			dept_id,
			dept_name,
			email,
			phn_no,
			user_type
			FROM user_view";
        return DB::select($query);
    }

    public static function get_user_count()
    {
        $query = "SELECT Count(user_id) AS cnt FROM user_view";
        return DB::select($query)[0]->cnt;
    }

    public static function is_admin($user_id)
    {
        $user = User::get_user_by_id($user_id);
        if ($user == null) return false;
        if ($user->user_type == User::get_admin_user_type()) return true;
        if ($user->user_type == User::get_super_admin_user_type()) return true;
        return false;
    }

    public static function is_super_admin($user_id)
    {
        $user = User::get_user_by_id($user_id);
        if ($user == null) return false;
        if ($user->user_type == User::get_super_admin_user_type()) return true;
        return false;
    }

    public static function get_user_type_value($user_id)
    {
        $type = User::get_user_type($user_id);
        $arr = ['superAdmin' => 1, 'admin' => 2, 'normal' => 3];
        return $arr[$type];
    }

    public static function compare_user($user_id1, $user_id2)
    {
        $u1_i = User::get_user_type_value($user_id1);
        $u2_i = User::get_user_type_value($user_id2);
        if ($u1_i < $u2_i) return 1;
        if ($u1_i > $u2_i) return -1;
        return 0;
    }

    public static function get_super_admin_user_type()
    {
        return 'superAdmin';
    }

    public static function get_admin_user_type()
    {
        return 'admin';
    }

    public static function get_new_user_type()
    {
        return 'normal';
    }

    public static function get_all_user_type($user_id)
    {
        $arr = ['superAdmin' => 1, 'admin' => 2, 'normal' => 3];
        $tv = User::get_user_type_value($user_id);
        $result = [];
        foreach ($arr as $key => $value) {
            if ($tv <= $value)
                array_push($result, $key);
        }
        return $result;
    }


    public static function get_name($user_id)
    {
        $user = User::get_user_by_id($user_id);
        if ($user == null) return null;
        return $user->user_name;
    }

    public static function get_email($user_id)
    {
        $user = User::get_user_by_id($user_id);
        if ($user == null) return null;
        return $user->email;
    }

    public static function get_user_type($user_id)
    {
        $user = User::get_user_by_id($user_id);
        if ($user == null) return null;
        return $user->user_type;
    }

    public static function login_user($user_id)
    {
        $user_id = Utility::is_integer($user_id);
        if ($user_id == null) return false;
        if (Utility::is_loged_in()) return false;
        session()->put('user_id', $user_id);
        return true;
    }

    public static function logout_user()
    {
        if (!Utility::is_loged_in()) return false;
        User::clear_remember_id(session()->get('user_id'));
        session()->forget('user_id');
        return true;
    }

    public static function insert_remember_id($user_id)
    {
        $length = 32;
        do {
            $rem_id = str_random($length);
            $length++;
            $user = User::get_user_by_rem_id($rem_id);
        } while ($user != null);
        DB::update("UPDATE users SET remember_token = ? WHERE id = ? ", [$rem_id, $user_id]);
        return $rem_id;
    }

    public static function clear_remember_id($user_id)
    {
        DB::update("UPDATE users SET remember_token = NULL WHERE id = ? ", [$user_id]);
    }

    public static function get_user_by_rem_id($rem_id)
    {
        $e = DB::select('SELECT * from user_view WHERE remember_token = ?', [$rem_id]);
        if (count($e) < 1 || $e == null) {
            return null;
        }
        return $e[0];
    }

    public static function is_loged_in()
    {
        return Utility::is_loged_in();
    }

    public static function insert_password_token($user_id)
    {
        $length = 32;
        do {
            $token = str_random($length);
            $length++;
            $user = User::get_user_by_password_token($token);
        } while ($user != null);
        DB::update("INSERT INTO reset_password(user_id,token) VALUES(?,?) ", [$user_id, $token]);
        return $token;
    }

    public static function get_user_by_password_token($token)
    {
        $e = DB::select('SELECT * from reset_password WHERE token = ?', [$token]);
        if (count($e) < 1 || $e == null) {
            return null;
        }
        $user_id = $e[0]->user_id;
        return User::get_user_by_id($user_id);
    }

    public static function get_user_password_token($user_id)
    {
        $e = DB::select('SELECT * from reset_password WHERE user_id = ?', [$user_id]);
        if (count($e) < 1 || $e == null) {
            return null;
        }
        return $e[0]->token;
    }

    public static function delete_user_password_token($token)
    {
        DB::delete('DELETE FROM reset_password WHERE token = ?', [$token]);
    }

    public static function get_student_total_attendance_list()
    {
        $query = "SELECT SUM(attn) As attn,
		name,
		roll_no,
		student_id
	  FROM
		suject_attendence_view_1
	  GROUP BY
		student_id,name,roll_no
	  ORDER BY
		attn DESC;";
        return DB::select($query);
    }

    public static function get_top_4_student_of_teacher($teacher_id)
    {
        $query = "SELECT SUM(mark_count) AS attn,
					NAME,
					roll_no,
					student_id,
					teacher_name
					FROM subject_attn_student_view
					WHERE teacher_id = ?
					GROUP BY student_id,NAME,roll_no
					ORDER BY attn DESC
					LIMIT 4;";
        return DB::select($query, [$teacher_id]);
    }

}
