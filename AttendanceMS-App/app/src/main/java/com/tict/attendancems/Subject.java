package com.tict.attendancems;

import android.database.sqlite.SQLiteDatabase;

import org.json.JSONException;
import org.json.JSONObject;

import static java.lang.String.format;

/**
 * Created by SD on 11/22/2017.
 */

public class Subject {
    private static final Subject ourInstance = new Subject();

    public static Subject getInstance() {
        return ourInstance;
    }

    private Database db ;

    private Subject() {
        db = Database.getInstance();
    }


    static void truncate(SQLiteDatabase db){
        db.execSQL("DELETE FROM subject;");
    }

    static void insert(SQLiteDatabase db, JSONObject subject) throws JSONException {
        String qry = "INSERT INTO subject VALUES ( '%s' , '%s' , %d ); ";
        String ex;
        String code = subject.getString("code");
        String name = subject.getString("name");
        int batch_id = subject.getInt("batch_id");
        ex = format(qry, code, name, batch_id);
        db.execSQL(ex);
    }

    static void create_table(SQLiteDatabase db)
    {
        String qry1 = "CREATE TABLE IF NOT EXISTS `subject` (" +
                " `code` TEXT NOT NULL UNIQUE," +
                " `name` TEXT," +
                " `batch_id` INTEGER NOT NULL," +
                " FOREIGN KEY(`batch_id`) " +
                " REFERENCES `batch`(`batch_id`) ON UPDATE RESTRICT ON DELETE RESTRICT," +
                " PRIMARY KEY(`code`) )";
        db.execSQL(qry1);
    }

    static void drop_table(SQLiteDatabase db)
    {
        String qry1 = "DROP TABLE IF EXISTS subject" ;
        db.execSQL(qry1);
    }
}
