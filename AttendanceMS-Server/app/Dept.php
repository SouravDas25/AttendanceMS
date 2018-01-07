<?php

namespace App;

use DB;

class Dept
{
    public static function get_all_dept()
    {
        /*
        * id
        * name
        * full_name
        * course_years
        * hod
        */
        return DB::select('SELECT *FROM dept ORDER BY name;');
    }

    public static function search_dept($search_text) // " ' OR 1 OR LIKE '" LIKE '%' OR 1 OR LIKE '%'
    {
        $search_text = "%" . $search_text . "%";
        $query = "SELECT * FROM dept_view 
        WHERE dept_name LIKE ? OR
        full_name LIKE ? OR
        course_years LIKE ? ";
        return DB::select($query, [$search_text, $search_text, $search_text]);
    }

    public static function get_from_id($id)
    {
        $query = "SELECT * FROM dept_view WHERE id = ? ";
        $r = DB::select($query, [$id]);
        if (count($r) < 1 || $r == null) return null;
        return $r[0];
    }

    public static function insert_and_return_id($dept_name, $dept_fullname, $dept_course_years)
    {
        DB::insert('INSERT INTO dept(name,full_name,course_years) VALUES(?,?,?) ', [$dept_name, $dept_fullname, $dept_course_years]);
        $dept_id = DB::select('SELECT LAST_INSERT_ID() AS id;')[0]->id;
        for ($i = 1; $i <= $dept_course_years * 2; $i++) {
            DB::insert('INSERT INTO batch(sem_no,dept_id) VALUES(?,?) ', [$i, $dept_id]);
        }
        return $dept_id;
    }

    public static function update_from_id($id, $dept_name, $dept_fullname, $dept_course_years)
    {
        DB::update('UPDATE dept SET name = ?,full_name = ?,course_years=? WHERE id = ?',
            [$dept_name, $dept_fullname, $dept_course_years, $id]);
    }

    public static function delete_from_id($id)
    {
        DB::delete('DELETE FROM batch WHERE dept_id = ? ', [$id]);
        DB::delete('DELETE FROM dept WHERE id = ? ', [$id]);
    }

    public static function get_all_active_batch_of_dept($dept_id)
    {
        $query = "SELECT batch_no,
        year(start_date) AS start_date ,
        dept_id,
        dept_name,
        sem_no,
        course_years,
        current_year,
        batch_id FROM batch_dept_sem_view WHERE dept_id = ? AND current_year <= course_years Order BY current_year";
        return DB::select($query, [$dept_id]);
    }

    public static function get_all_batch_of_dept($dept_id)
    {
        $query = "SELECT batch_no,
        year(start_date) AS start_date ,
        dept_id,
        dept_name,
        sem_no,
        course_years,
        current_year,
        batch_id FROM batch_dept_sem_view WHERE dept_id = ? Order BY current_year";
        return DB::select($query, [$dept_id]);
    }

    public static function get_all_active_batches()
    {
        $query = "SELECT batch_no,
        year(start_date) AS start_date ,
        dept_id,
        dept_name,
        sem_no,
        course_years,
        current_year,
        batch_id FROM batch_dept_sem_view WHERE course_years >= current_year ";
        return DB::select($query);
    }

    public static function get_all_batches()
    {
        $query = "SELECT batch_id, sem_no, dept_name, dept_id FROM batch_dept_view_2 ";
        return DB::select($query);
    }

    public static function get_all_batch_id_in_dept($dept_id)
    {
        $query = "SELECT batch_id, sem_no, dept_name, dept_id FROM batch_dept_view_2
        WHERE dept_id = ? ";
        return DB::select($query, [$dept_id]);
    }

    public static function get_all_passOut_batches()
    {
        $query = "SELECT 
        batch_no,
        year(start_date) AS start_date ,
        dept_id,
        dept_name,
        sem_no,
        course_years,
        current_year,
        batch_id FROM batch_dept_sem_view WHERE course_years < current_year ";
        return DB::select($query);
    }
}
