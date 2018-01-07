<?php

namespace App;

use DB;
use App\Student;
use App\Dept;

use App\Utility;
use Mockery\Exception;

class Batch
{
    public static function get_batch($batch_no)
    {
        /*
         * batch_no , 
         * start_Date , 
         * dept_id
         * dept_name
         * sem_no
         * course_years
         * current_year
         */
        $query = "SELECT batch_no , year(start_date) AS start_date, dept_id , 
        dept_name , sem_no , course_years , current_year
        FROM dept_batch_view_3 WHERE batch_no = ? ";
        $query = DB::select($query, [$batch_no]);
        if (count($query) < 1 || !$query) return null;
        return $query[0];
    }

    public static function get_all_batches()
    {
        return Dept::get_all_batches();
    }

    public static function get_all_active_batches()
    {
        return Dept::get_all_active_batches();
    }

    public static function get_all_active_batch_of_dept($dept_id)
    {
        return Dept::get_all_active_batch_of_dept($dept_id);
    }

    public static function search_batch($search_text)
    {
        $search_text = "%$search_text%";
        $query = " SELECT batch_no , year(start_date) As start_date, dept_id , dept_name,sem_no,
        course_years,current_year
        FROM dept_batch_view_3
        WHERE dept_name like ? OR sem_no like ? ORDER BY (current_year);";
        return DB::select($query, [$search_text, $search_text]);
    }

    public static function get_all_students_total_attendance_in_batch($batch_no)
    {
        $students = Student::get_all_students_in_batch($batch_no);
        foreach ($students as $student) {
            $student->student_id = $student->student_id;
            $student->attn = Student::get_total_attendence_of_student($student->student_id);
        }
        return $students;
    }

    public static function get_total_classes_taken_in_batch($batch_no)
    {
        /*
         * total class
         */
        $query = "SELECT SUM(mark_count) AS cnt FROM active_day WHERE batch_no = ?";
        $r = DB::select($query, [$batch_no]);
        if (count($r) < 1) return 0;
        return $r[0]->cnt;
    }

    public static function insert_batch($start_date, $dept_id)
    {
        $start_date = Utility::is_date('Y-m-d', $start_date);
        if ($start_date == null) throw \Exception::class("Date Format incorrect");
        DB::insert("INSERT INTO student_batch(start_date,dept_id) 
        VALUES(str_to_date(?,'%Y-%m-%d'),?) ", [$start_date, $dept_id]);
        $batch_no = DB::select('SELECT LAST_INSERT_ID() AS id;')[0]->id;
        return $batch_no;
    }

    public static function update_batch($batch_no, $start_date, $dept_id)
    {
        DB::update('UPDATE student_batch SET start_date = ?, dept_id = ? 
        WHERE batch_no = ? ', [$start_date, $dept_id, $batch_no]);
    }

    public static function delete_batch($batch_no)
    {
        DB::delete('DELETE FROM student WHERE batch_no = ?', [$batch_no]);
        DB::delete('DELETE FROM student_batch WHERE batch_no = ?', [$batch_no]);
    }

    public static function get_total_classes_taken_in_batch_by_sem($batch_no, $sem_no)
    {
        /*
         * total class
         */
        $query = "SELECT SUM(mark_count) AS cnt
        FROM
          active_day
        JOIN
          sub_dept_view_1 USING(subject_code)
        WHERE
          batch_no = ? AND sem_no = ? ";
        $r = DB::select($query, [$batch_no, $sem_no]);
        if (count($r) < 1) return 0;
        return $r[0]->cnt;
    }


}
