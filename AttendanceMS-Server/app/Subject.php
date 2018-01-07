<?php

namespace App;

use DB;
use App\Batch;
use App\Student;

class Subject
{
    public static function search_subject($search_text)
    {
        $search_text = "%$search_text%";
        $query = "SELECT * FROM sub_dept_view_1 
        WHERE subject_name LIKE ? OR
        subject_code LIKE ? OR
        dept_name LIKE ? ORDER BY dept_id , sem_no ASC";
        return DB::select($query, [$search_text, $search_text, $search_text]);
    }

    public static function search_subject_with_batch_id($search_text, $batch_id)
    {
        $search_text = "%$search_text%";
        $query = "SELECT * FROM sub_dept_view_1 
        WHERE (subject_name LIKE ? OR
        subject_code LIKE ? OR
        dept_name LIKE ?) AND batch_id = ? ORDER BY dept_id , sem_no ASC";
        return DB::select($query, [$search_text, $search_text, $search_text, $batch_id]);
    }

    public static function get_subject_by_code($subject_code)
    {
        $r = DB::table('subject')->where('code', $subject_code)->get();
        if (count($r) < 1 || $r == null) {
            return null;
        }
        return $r[0];
    }

    public static function insert_and_return_code($subject_name, $subject_code, $batch_id)
    {
        DB::insert('INSERT INTO subject(name,code,batch_id) VALUES(?,?,?) ', [$subject_name, $subject_code, $batch_id]);
        return $subject_code;
    }

    public static function update_from_code($subject_name, $subject_code, $batch_id)
    {
        DB::update('UPDATE subject SET name = ?  , batch_id = ? WHERE code = ? ',
            [$subject_name, $batch_id, $subject_code]);
    }

    public static function delete_from_code($subject_code)
    {
        DB::delete('DELETE FROM subject WHERE code = ?', [$subject_code]);
    }

    public static function get_all_students_total_attendance_in_subject($batch_no, $subject_code)
    {
        $students = Student::get_all_students_in_batch($batch_no);
        foreach ($students as $student) {
            $student->student_id = $student->student_id;
            $student->subject_code = $subject_code;
            $student->attn = Student::get_attendence_in_subject($student->student_id, $subject_code);
        }
        return $students;
    }

    public static function get_total_classes_taken_in_subject($batch_no, $subject_code)
    {
        /*
         * total class
         */
        $query = "SELECT SUM(mark_count) AS cnt FROM active_day WHERE batch_no = ? AND subject_code = ? ";
        $r = DB::select($query, [$batch_no, $subject_code]);
        if (count($r) < 1) return 0;
        return $r[0]->cnt;
    }

    public static function get_all_subject_in_batch($batch_no)
    {
        /*
         * array of :
         *  subject_name 
         *  subject_code
         */
        $b = Batch::get_batch($batch_no);
        return Subject::get_all_subject_in_sem($b->dept_id, $b->sem_no);
    }

    public static function get_all_subject_in_sem($dept_id, $sem_no)
    {
        /*
         * array of :
         *  subject_name 
         *  subject_code
         */
        $query = " SELECT subject_name,subject_code
        FROM sub_dept_view_1 WHERE dept_id = ? AND sem_no = ? ORDER BY subject_code;";
        return DB::select($query, [$dept_id, $sem_no]);
    }

    public static function get_batch_by_subject_code($subject_code)
    {
        $subject = Subject::get_subject_by_code($subject_code);
        if (!$subject) return null;
        $query = "SELECT * FROM batch_dept_sem_view WHERE batch_id = ? ;";
        $r = DB::select($query, [$subject->batch_id]);
        if (count($r) < 1 || $r == null) return null;
        return $r[0];
    }

    public static function get_all_subject_in_department($dept_id)
    {
        /*
         * array of :
         *  subject_name 
         *  subject_code
         */
        $query = " SELECT subject_name,subject_code
        FROM sub_dept_view_1 WHERE dept_id = ? ORDER BY subject_code;";
        return DB::select($query, [$dept_id]);
    }
}
