<?php

namespace App;

use DB;

class Normalize
{
    public static function update($student_id, $sem_no, $attn, $total)
    {
        DB::update("UPDATE lt_student_attendance SET attendance = ? , total = ? WHERE 
        student_id = ? AND sem_no = ?", [$attn, $total, $student_id, $sem_no]);
    }

    public static function delete($student_id, $sem_no)
    {
        DB::update("DELETE FROM lt_student_attendance WHERE 
        student_id = ? AND sem_no = ?", [$student_id, $sem_no]);
    }

    public static function insert($student_id, $sem_no, $attn, $total)
    {
        DB::insert("INSERT INTO lt_student_attendance(attendance,total,student_id,sem_no) 
        VALUES(?,?,?,?)", [$attn, $total, $student_id, $sem_no]);
    }

    public static function get_lt_student_record($student_id, $sem_no)
    {
        $query = "SELECT * FROM lt_student_attendance WHERE student_id = ? AND sem_no = ?";
        $query = DB::select($query, [$student_id, $sem_no]);
        if (count($query) <= 0 || $query == null) return null;
        return $query[0];
    }

    public static function get_active_day_by_sem_no($sem_no, $batch_no)
    {
        $query = "SELECT active_day_id FROM active_day JOIN sub_dept_view_1 Using(subject_code) 
        WHERE sem_no = ? AND batch_no = ? ;";
        $query = DB::select($query, [$sem_no, $batch_no]);
        if (count($query) <= 0 || $query == null) return [];
        return $query;
    }

    public static function get_all_teacher_taking_subject($subject_code, $batch_no)
    {
        $query = "SELECT teacher_id FROM active_day
        WHERE subject_code = ? AND batch_no = ? ;";
        $query = DB::select($query, [$subject_code, $batch_no]);
        if (count($query) <= 0 || $query == null) return null;
        return $query;
    }

    public static function sem_normalize()
    {
        $pbatches = Dept::get_all_active_batches();
        foreach ($pbatches as $batch) {
            $students = DB::select('SELECT id AS student_id FROM student WHERE batch_no = ?', [$batch->batch_no]);
            $curnt_sem = $batch->sem_no;
            $previous_sem = $curnt_sem - 1;
            if ($previous_sem == 0) continue;
            foreach ($students as $student) {
                $overview = Student::get_student_overview_in_total($student->student_id, $batch->batch_no, $previous_sem);
                $attendance = $overview['attn'];
                $total = $overview['total'];
                if ($total == 0) continue;
                $row = Normalize::get_lt_student_record($student->student_id, $previous_sem);
                if ($row == null) {
                    Normalize::insert($student->student_id, $previous_sem, $attendance, $total);
                } else {
                    $attendance += $row->attendance;
                    $total += $row->total;
                    Normalize::update($student->student_id, $previous_sem, $attendance, $total);
                }
            }
            $active_days = Normalize::get_active_day_by_sem_no($previous_sem, $batch->batch_no);
            foreach ($active_days as $active_day) {
                DB_Class::mydelete($active_day->active_day_id);
            }
        }
        return true;
    }

    public static function generate()
    {
        DB::delete(" TRUNCATE TABLE lt_student_attendance; ");
        DB::beginTransaction();
        $batches = DB::select('SELECT batch_no FROM student_batch');
        foreach ($batches as $batch) {
            $batch = Batch::get_batch($batch->batch_no);
            $students = DB::select('SELECT id FROM student WHERE batch_no = ? ', [$batch->batch_no]);

            for ($i = 1; $i < $batch->sem_no; $i++) {
                $total = rand(0, 150);
                foreach ($students as $student) {
                    $attendance = rand(0, $total);
                    Normalize::insert($student->id, $i, $attendance, $total);
                }
            }
        }
        DB::commit();
        return true;
    }

    public static function generate_class()
    {
        DB::statement(" TRUNCATE TABLE attendence; ");
        DB::unprepared("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE active_day;SET FOREIGN_KEY_CHECKS=1; ");
        $batches = DB::select('SELECT batch_no FROM student_batch');
        DB::beginTransaction();
        foreach ($batches as $batch) {
            $batch = Batch::get_batch($batch->batch_no);
            $students = DB::select('SELECT id FROM student WHERE batch_no = ? ', [$batch->batch_no]);
            $subjects = Subject::get_all_subject_in_batch($batch->batch_no);

            for ($i = 1; $i < 50; $i++) {
                $subject_code = $subjects[rand(0, count($subjects) - 1)]->subject_code;
                $total = rand(0, count($students));
                $date = Utility::rand_date("1-" . Utility::get_sem_start_month(date("m")) . "-" . date("Y"));
                $teacher_id = 1;
                $mark_count = rand(1, 3);
                $head_count = rand(10, $total);
                $query = "INSERT INTO active_day(class_date,class_topic,class_remarks,
                teacher_id,subject_code,batch_no,mark_count)
                VALUES ( '$date' , 'Auto-Generated' , 'Auto-Generated' , $teacher_id , '$subject_code' , $batch->batch_no ,$mark_count)";
                DB::insert($query);
                $adi = DB::select('SELECT LAST_INSERT_ID() AS id;')[0]->id;
                $sl = [];
                for ($p = 1; $p < $head_count; $p++) {
                    $student_id = $students[rand(0, count($students) - 1)]->id;
                    $sl[$student_id] = true;
                }
                foreach ($sl as $student_id => $value) {
                    DB::insert('INSERT INTO attendence(	student_id, active_day_id) values (?, ?)', [$student_id, $adi]);
                }
            }
        }
        DB::commit();
        return true;
    }
}

