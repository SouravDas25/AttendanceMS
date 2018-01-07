<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class DB_Class extends Model
{

    public static function mydelete($id)
    {
        $query = "DELETE FROM attendence WHERE active_day_id = ? ";
        DB::delete($query, [$id]);
        $query = "DELETE FROM active_day WHERE active_day_id =  ? ";
        DB::delete($query, [$id]);
    }

    public static function mycreate($teacher_id, $class, $students)
    {
        $query = "INSERT INTO active_day(class_date,class_topic,class_remarks,teacher_id,subject_code,batch_no,mark_count)
		VALUES (?, ?, ?,?, ?,? ,?)";
        DB::insert($query, [$class->date, $class->topic, $class->remarks, $teacher_id, $class->code, $class->batch_no, $class->mark_count]);

        $adi = DB::select('SELECT LAST_INSERT_ID() AS id;')[0]->id;
        foreach ($students as $student) {
            DB::insert('INSERT INTO attendence(	student_id, active_day_id) values (?, ?)', [$student[0], $adi]);
        }
    }

    public static function search_class($user_id, $search_text)
    {
        $search_text = "%$search_text%";
        $query = "SELECT * FROM active_day_view_1 
		WHERE teacher_id = ? AND (
			subject_code LIKE ? OR
			subject_name LIKE ? OR
			class_date LIKE ? OR
			class_date LIKE ? OR
			dept_name LIKE ? OR
			batch_current_year LIKE ?
		)
		ORDER BY class_date DESC";
        return DB::select($query, [$user_id, $search_text, $search_text, $search_text, $search_text, $search_text, $search_text]);
    }

    public static function get_all_class_in_days($user_id, $days)
    {
        $query = "SELECT * FROM active_day_view_1 
		WHERE teacher_id = ? AND DATEDIFF( CURRENT_DATE , class_date ) < ?
		ORDER BY class_date DESC";
        return DB::select($query, [$user_id, $days]);
    }

    public static function get_class_details_by_active_day_id($active_day_id)
    {
        $query = "SELECT * FROM active_day_view_1 
		WHERE active_day_id = ? ";
        $r = DB::select($query, [$active_day_id]);
        if (count($r) < 1 || $r == null) return null;
        return $r[0];
    }

    public static function get_class_attendance_list_by_active_day_id($active_day_id, $batch_no)
    {
        $query = "SELECT id, name , roll_no , active_day_id
		FROM student 
		LEFT JOIN( SELECT * FROM attendence WHERE active_day_id = ? ) c ON c.student_id = student.id 
		WHERE batch_no = ?
		ORDER BY roll_no";
        return DB::select($query, [$active_day_id, $batch_no]);
    }

    public static function get_no_classes_taken_by_teacher($user_id)
    {
        $query = "SELECT Count(active_day_id) AS cnt FROM active_day WHERE teacher_id = ? ";
        $query = DB::select($query, [$user_id]);
        if (count($query) < 1 || $query == null) return 0;
        return $query[0]->cnt;
    }

    public static function get_no_classes_marked_by_teacher($user_id)
    {
        $query = "SELECT Sum(mark_count) AS cnt FROM active_day WHERE teacher_id = ? ";
        $query = DB::select($query, [$user_id]);
        if (count($query) < 1 || $query == null) return 0;
        return $query[0]->cnt;
    }

    public static function get_max_head_count_by_teacher($user_id)
    {
        $query = "SELECT max(head_count) AS cnt FROM active_day_view_1 WHERE teacher_id = ? ";
        $query = DB::select($query, [$user_id]);
        if (count($query) < 1 || $query == null) return 0;
        return $query[0]->cnt;
    }

    public static function get_all_classes_of_subject_by_batch($subject_code, $batch_no)
    {
        $query = "SELECT * FROM active_day_view_1 
        WHERE subject_code = ? AND batch_no = ? ORDER BY class_date;";
        $query = DB::select($query, [$subject_code, $batch_no]);
        if (count($query) <= 0 || $query == null) return [];
        return $query;
    }

    public static function is_student_present_on($student_id, $active_day_id)
    {
        $query = 'SELECT student_id AS id FROM attendence 
		WHERE active_day_id = ? AND student_id = ? ';
        $query = DB::select($query, [$active_day_id, $student_id]);
        if (count($query) <= 0 || $query == null) return false;
        return true;
    }

}
