package com.tict.attendancems;

import android.database.sqlite.SQLiteDatabase;

import org.json.JSONException;
import org.json.JSONObject;

import static java.lang.String.format;

/**
 * Created by SD on 11/22/2017.
 */

public class Student {
    private static final Student ourInstance = new Student();

    public static Student getInstance() {
        return ourInstance;
    }

    private Database db ;

    private Student() {
        db = Database.getInstance();
    }


    static void truncateStudent(SQLiteDatabase db){
        db.execSQL("DELETE FROM student;");
    }
    static void truncateStudentBatch(SQLiteDatabase db){
        db.execSQL("DELETE FROM student_batch;");
    }

    static void insertStudent(SQLiteDatabase db, JSONObject student) throws JSONException {
        String qry = "INSERT INTO student VALUES ( %d , '%s' , %d , %d ); ";
        String ex;
        int id = student.getInt("id");
        String name = student.getString("name");
        int roll = student.getInt("roll_no");
        int batch_no = student.getInt("batch_no");
        ex = format(qry, id , name , roll , batch_no);
        db.execSQL(ex);
    }

    static void insertStudentBatch(SQLiteDatabase db, JSONObject sb) throws JSONException {
        String qry = "INSERT INTO student_batch VALUES ( %d , '%s' , %d ); ";
        String ex;
        int batch_no = sb.getInt("batch_no");
        String start_date = sb.getString("start_date");
        int dept_id = sb.getInt("dept_id");

        ex = format(qry, batch_no , start_date,dept_id);
        db.execSQL(ex);
    }

    static void create_table(SQLiteDatabase db)
    {
        String qry1 = "CREATE TABLE IF NOT EXISTS `student_batch` (" +
                " `batch_no` INTEGER NOT NULL UNIQUE," +
                " `start_date` DATE NOT NULL," +
                " `dept_id` INTEGER NOT NULL," +
                " PRIMARY KEY(`batch_no`)," +
                " FOREIGN KEY(`dept_id`) REFERENCES `dept`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT )";
        db.execSQL(qry1);
        qry1 = "CREATE TABLE IF NOT EXISTS `student` (" +
                " `id` INTEGER NOT NULL UNIQUE," +
                " `name` TEXT NOT NULL," +
                " `roll_no` INTEGER NOT NULL," +
                " `batch_no` INTEGER NOT NULL," +
                " PRIMARY KEY(`id`)," +
                " FOREIGN KEY(`batch_no`) REFERENCES `student_batch`(`batch_no`) )";
        db.execSQL(qry1);
        qry1 = "CREATE VIEW dept_batch_view_3 AS \n" +
                "SELECT \n" +
                "  student_batch.batch_no AS batch_no,\n" +
                "  student_batch.start_date AS start_date,\n" +
                "  student_batch.dept_id AS dept_id,\n" +
                "  dept.name AS dept_name,\n" +
                "  cast((julianday('now') - julianday(student_batch.start_date)) / 182.5 AS INT ) + 1 AS sem_no,\n" +
                "  dept.course_years AS course_years,\n" +
                "  strftime('%Y',date('now')) - strftime('%Y',date(student_batch.start_date)) + 1 AS current_year\n" +
                "FROM\n" +
                "  student_batch\n" +
                "JOIN\n" +
                "  dept ON dept.id = student_batch.dept_id;";
        db.execSQL(qry1);
        qry1 = "CREATE VIEW batch_dept_view_2 AS \n" +
                "SELECT\n" +
                "  batch.batch_id AS batch_id,\n" +
                "  batch.sem_no AS sem_no,\n" +
                "  dept.name AS dept_name,\n" +
                "  dept.id AS dept_id\n" +
                "FROM\n" +
                "  batch\n" +
                "JOIN\n" +
                "  dept ON dept.id = batch.dept_id\n" +
                "ORDER BY\n" +
                "  dept.name,\n" +
                "  batch.sem_no;";
        db.execSQL(qry1);
        qry1 = "CREATE VIEW batch_view AS " +
                "SELECT\n" +
                "  a.batch_no AS batch_no,\n" +
                "  a.start_date AS start_date,\n" +
                "  a.dept_id AS dept_id,\n" +
                "  a.dept_name AS dept_name,\n" +
                "  b.sem_no AS sem_no,\n" +
                "  a.course_years AS course_years,\n" +
                "  a.current_year AS current_year,\n" +
                "  b.batch_id AS batch_id\n" +
                "FROM\n" +
                "  dept_batch_view_3 a\n" +
                "LEFT JOIN\n" +
                "  batch_dept_view_2 b ON(\n" +
                "    (a.dept_id = b.dept_id) AND (a.sem_no = b.sem_no)\n" +
                "  )";
        db.execSQL(qry1);
    }

    static void drop_table(SQLiteDatabase db)
    {
        String qry1 = "DROP TABLE IF EXISTS student_batch" ;
        db.execSQL(qry1);
        qry1 = "DROP TABLE IF EXISTS student" ;
        db.execSQL(qry1);
        qry1 = "DROP VIEW IF EXISTS dept_batch_view_3" ;
        db.execSQL(qry1);
        qry1 = "DROP VIEW IF EXISTS batch_dept_view_2" ;
        db.execSQL(qry1);
        qry1 = "DROP VIEW IF EXISTS batch_view" ;
        db.execSQL(qry1);
    }
}
