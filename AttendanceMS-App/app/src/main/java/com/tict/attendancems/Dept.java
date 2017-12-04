package com.tict.attendancems;

import android.database.sqlite.SQLiteDatabase;

import org.json.JSONException;
import org.json.JSONObject;

import static java.lang.String.format;

/**
 * Created by SD on 11/22/2017.
 */

public class Dept {
    private static final Dept ourInstance = new Dept();

    public static Dept getInstance() {
        return ourInstance;
    }

    private Database db ;

    private Dept() {
        db = Database.getInstance();
    }

    static void truncateDept(SQLiteDatabase db){
        db.execSQL("DELETE FROM dept;");
    }
    static void truncateBatch(SQLiteDatabase db){
        db.execSQL("DELETE FROM batch;");
    }

    static void insertDept(SQLiteDatabase db,int id,String name,String fullname , int years){
        String qry = "INSERT INTO dept VALUES ( %d , '%s' , '%s' , %d ); ";
        String ex;
        ex = format(qry, id, name, fullname, years);
        db.execSQL(ex);
    }

    static void insertDept(SQLiteDatabase db,JSONObject dept) throws JSONException {
        int id = dept.getInt("id");
        String name = dept.getString("name");
        String fullname = dept.getString("full_name") ;
        int years = dept.getInt("course_years");
        insertDept(db,id, name, fullname , years);
    }

    public static void insertBatch(SQLiteDatabase db,JSONObject batch) throws JSONException {
        String qry = "INSERT INTO batch VALUES ( %d , %d  , %d ); ";
        String ex;
        int id = batch.getInt("batch_id");
        int sem_no = batch.getInt("sem_no");
        int dept_id = batch.getInt("dept_id");
        ex = format(qry, id, sem_no, dept_id);
        db.execSQL(ex);
    }

    static void create_table(SQLiteDatabase db)
    {
        String qry1 = "CREATE TABLE IF NOT EXISTS `dept` (" +
                " `id` INTEGER NOT NULL UNIQUE," +
                " `name` TEXT," +
                " `fullname` TEXT, " +
                "`course_years` INTEGER CHECK(course_years > 0)," +
                " PRIMARY KEY(`id`) )";
        db.execSQL(qry1);
        qry1 ="CREATE TABLE \"batch\" ( " +
                " `batch_id` INTEGER NOT NULL PRIMARY KEY ," +
                " `sem_no` INTEGER NOT NULL," +
                " `dept_id` INTEGER NOT NULL," +
                " FOREIGN KEY(`dept_id`) REFERENCES `dept`(`id`) ON UPDATE RESTRICT ON DELETE RESTRICT )";
        db.execSQL(qry1);
    }

    static void drop_table(SQLiteDatabase db)
    {
        String qry1 ;
        qry1 = "DROP TABLE IF EXISTS batch" ;
        db.execSQL(qry1);
        qry1 = "DROP TABLE IF EXISTS dept" ;
        db.execSQL(qry1);
    }
}
