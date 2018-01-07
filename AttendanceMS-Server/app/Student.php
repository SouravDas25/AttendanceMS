<?php namespace App;

use DB;

class Student
{

    public static function search_student($search_text)
    {
        $search_text = "%$search_text%";
        $query = "SELECT * FROM student_view_1 
		WHERE student_dept LIKE ? OR
		student_name LIKE ? OR
		year LIKE ? OR
		student_roll LIKE ? ";
        return DB::select($query, [$search_text, $search_text, $search_text, $search_text]);
    }

    public static function get_student($roll_no, $batch_no)
    {
        /*
         * id
         * name
         * roll_no
         * batch_no 
         */
        $query = "SELECT * FROM student_view_1 WHERE student_roll = ? AND batch_no = ? ";
        $r = DB::Select($query, [$roll_no, $batch_no]);
        if (count($r) < 1) return null;
        return $r[0];
    }

    public static function get_student_by_id($student_id)
    {
        /*
         * id
         * name
         * roll_no
         * batch_no 
         */
        $query = "SELECT * FROM student_view_1 WHERE student_id = ? ";
        $r = DB::Select($query, [$student_id]);
        if (count($r) < 1) return null;
        return $r[0];
    }

    public static function get_all_students_in_batch($batch_no)
    {
        $query = "SELECT * FROm student_view_1 WHERE batch_no = ?";
        return DB::select($query, [$batch_no]);
    }

    public static function get_total_attendence_of_student($student_id)
    {
        /*
         * attendence
         
        $query = "SELECT count(student_attendence.active_day_id)  AS attn
        from active_day
        join student_attendence  Using(active_day_id)
        WHERE active_day.batch_no = ? AND student_attendence.student_id = ?
		group by student_attendence.student_id,active_day.active_day_id;";
		*/
        $query = "SELECT SUM(mark_count) AS attn
				FROM student_attendence
				JOIN
					active_day USING(active_day_id)
                WHERE student_id = ? ";
        $r = DB::select($query, [$student_id]);
        if (count($r) < 1 || $r == null) return 0;
        if ($r[0]->attn == null) return 0;
        return $r[0]->attn;
    }

    public static function get_total_attendence_of_student_by_sem($student_id, $batch_no, $sem_no)
    {
        /*
         * attendence
         */
        $query = "SELECT 
			IF(SUM(mark_count) is Null , 0 , SUM(mark_count)) As attn
			FROM
				student_attendence
			JOIN
				active_day USING(active_day_id)
            
			JOIN 
				sub_dept_view_1 USING(subject_code)
            WHERE
				active_day.batch_no = ? AND student_attendence.student_id = ? AND sem_no = ?";
        $r = DB::select($query, [$batch_no, $student_id, $sem_no]);
        if (count($r) < 1) return 0;
        return $r[0]->attn;
    }

    public static function get_attendence_in_subject($student_id, $subject_code)
    {
        /*
         * attendence
         */
        $query = "SELECT attn FROM suject_attendence_view_1 
        WHERE student_id = ? AND subject_code = ? ";
        $r = DB::Select($query, [$student_id, $subject_code]);
        if (count($r) < 1) return 0;
        return $r[0]->attn;
    }

    public static function insert_student($name, $roll_no, $batch_no)
    {
        $query = "INSERT INTO student(name,roll_no,batch_no) VALUES(?,?,?) ";
        DB::insert($query, [$name, $roll_no, $batch_no]);
        return DB::select('SELECT LAST_INSERT_ID() AS id;')[0]->id;
    }

    public static function update_student($id, $name, $roll_no, $batch_no)
    {
        DB::update('UPDATE student SET name = ? , roll_no = ? , batch_no = ? WHERE id = ? ', [$name, $roll_no, $batch_no, $id]);
    }

    public static function delete_student($id)
    {
        DB::delete('DELETE FROM student WHERE id = ?', [$id]);
    }

    public static function get_total_class_taken_in_month($month, $batch_no)
    {
        $query = "SELECT * 
		FROM (SELECT Sum(mark_count) AS cnt, month(class_date) AS d 
		FROM active_day_view_1  WHERE batch_no = ? GROUP BY month(class_date)) A 
		WHERE A.d = ?";
        $query = DB::select($query, [$batch_no, $month]);
        if (count($query) < 1 || $query == null) return 0;
        return $query[0]->cnt;
    }

    public static function get_total_class_of_student_in_month($student_id, $month)
    {
        $query = "SELECT SUM(mark_count) AS attn  
		FROM subject_attn_student_view
		WHERE student_id = ? AND month(class_date) = ?
		GROUP BY month(class_date) , student_id";
        $query = DB::select($query, [$student_id, $month]);
        if (count($query) < 1 || $query == null) return 0;
        return $query[0]->attn;
    }

    public static function get_student_overview_in_sem($student_id, $sem_no)
    {
        $std = Student::get_student_by_id($student_id);
        $subjects = Subject::get_all_subject_in_sem($std->dept_id, $sem_no);
        $batch_no = $std->batch_no;
        $result = [];
        foreach ($subjects as $subject) {
            $sub = [];
            $sub['code'] = $subject->subject_code;
            $sub['name'] = $subject->subject_name;
            $sub['attn'] = Student::get_attendence_in_subject($student_id, $subject->subject_code);
            $sub['total'] = Subject::get_total_classes_taken_in_subject($batch_no, $subject->subject_code);
            array_push($result, $sub);
        }
        array_push($result, ['code' => 'Avg', 'name' => 'Total',
            'attn' => Student::get_total_attendence_of_student($student_id),
            'total' => Batch::get_total_classes_taken_in_batch($batch_no)]);
        return $result;
    }


    public static function get_student_overview_in_total($student_id, $batch_no, $sem_no)
    {
        $result = ['attn' => Student::get_total_attendence_of_student_by_sem($student_id, $batch_no, $sem_no),
            'total' => Batch::get_total_classes_taken_in_batch_by_sem($batch_no, $sem_no)];
        return $result;
    }

    public static function get_student_all_sem_overview($student_id)
    {
        return DB::select("SELECT * FROM lt_student_attendance WHERE student_id = ? ORDER BY sem_no ASC", [$student_id]);
    }

    public static function get_student_avg_overview_of_all_sems($student_id)
    {
        $query = "SELECT sum(attendance) As attn , sum(total) AS total FROM lt_student_attendance WHERE student_id = ?";
        $query = DB::select($query, [$student_id]);
        if (count($query) < 1 || $query == null) return null;
        $query = $query[0];
        if ($query->attn == null) $query->attn = 0;
        if ($query->total == null) $query->total = 0;
        return $query;
    }

}
