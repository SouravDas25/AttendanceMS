package com.tict.attendancems;

import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import org.json.JSONObject;

/**
 * Created by SD on 11/21/2017.
 */

public class Database extends SQLiteOpenHelper {

    public static Database db = null ;

    public static void initInstance(Context context)
    {
        if(db==null)
        db = new Database(context);
    }

    public static Database getInstance()
    {
        return db;
    }

    private static final String DATABASE_NAME = "ATTENDANCE_MS.db";
    private static final int DATABASE_VERSION = 3;

    public Database(Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    Cursor select(String query, String[] args)
    {
        return this.getReadableDatabase().rawQuery(query,args);
    }

    public static SQLiteDatabase beginTransaction()
    {
        SQLiteDatabase db = Database.getInstance().getWritableDatabase();
        db.execSQL("BEGIN TRANSACTION;");
        return db;
    }

    public static void commit(SQLiteDatabase db)
    {
        db.execSQL("COMMIT;");
    }

    public static void rollback(SQLiteDatabase db)
    {
        db.execSQL("ROLLBACK;");
    }

    public static void query(SQLiteDatabase db, String query, Object[] bindArgs)
    {
        db.execSQL(query,bindArgs);
    }

    public static void query(SQLiteDatabase db,String query)
    {
        db.execSQL(query);
    }

    void statement(String query, Object[] bindArgs)
    {
        SQLiteDatabase db = this.getWritableDatabase();
        db.execSQL("BEGIN TRANSACTION;");
        db.execSQL(query,bindArgs);
        db.execSQL("COMMIT;");
        //db.execSQL("END TRANSACTION;");
    }

    void statement(String query)
    {
        SQLiteDatabase db = this.getWritableDatabase();
        db.execSQL("BEGIN TRANSACTION;");
        db.execSQL(query);
        db.execSQL("COMMIT;");
        //db.execSQL("END TRANSACTION;");
    }

    void insert(String query, Object[] bindArgs)
    {
        statement(query,bindArgs);
    }

    void insert(String query)
    {
        statement(query);
    }

    void update(String query, Object[] bindArgs)
    {
        statement(query,bindArgs);
    }

    void update(String query)
    {
        statement(query);
    }

    void delete(String query, Object[] bindArgs)
    {
        statement(query,bindArgs);
    }

    void delete(String query)
    {
        statement(query);
    }


    @Override
    public void onCreate(SQLiteDatabase db) {
        User.create_table(db);
        Dept.create_table(db);
        Subject.create_table(db);
        Student.create_table(db);
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int ov, int nv) {
        if(ov < nv){
            User.drop_table(db);
            Subject.drop_table(db);
            Student.drop_table(db);
            Dept.drop_table(db);

            onCreate(db);
        }
    }
}
