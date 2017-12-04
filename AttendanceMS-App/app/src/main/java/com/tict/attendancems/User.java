package com.tict.attendancems;

import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.util.Log;

/**
 * Created by SD on 11/21/2017.
 */

public class User {

    public static User u = null;

    public static User getInstance()
    {
        if(u==null)u = new User();
        return u;
    }

    private String user_name= "";
    private String user_email= "";
    private String user_pass= "";
    private int user_id = -1;
    private Database db;

    static void create_table(SQLiteDatabase db)
    {
        String qry1 = "CREATE TABLE IF NOT EXISTS user(" +
                "id INT PRIMARY KEY," +
                "user_id INT," +
                "name TEXT," +
                "email TEXT," +
                "password TEXT );";
        db.execSQL(qry1);
        qry1 = "INSERT INTO user VALUES(1,null,'','','');";
        db.execSQL(qry1);
        qry1 = "CREATE TABLE IF NOT EXISTS pending(id INTEGER PRIMARY KEY AUTOINCREMENT," +
                "value TEXT," +
                "created_at DATETIME DEFAULT current_timestamp );";
        db.execSQL(qry1);
    }

    static void drop_table(SQLiteDatabase db)
    {
        String qry1 = "DROP TABLE IF EXISTS user" ;
        db.execSQL(qry1);
    }

    void logout()
    {
        user_id = -1;
        user_name = "";
        user_email = "";
        user_pass = "";
        db.update("UPDATE user SET user_id = ? , name = ?, email=? " +
                ", password=? " +
                " WHERE id = 1;",new Object[] {user_id,user_email,user_email,user_pass});
    }

    void setData(int id, String name, String email, String pass)
    {
        user_id = id;
        user_name = name;
        user_email = email;
        user_pass = pass;
        db.update("UPDATE user SET user_id = ? , name = ?, email=? " +
                ", password=? " +
                " WHERE id = 1;",new Object[] {id,name,email,pass});
        Log.i("database", user_name + user_email + user_pass );

    }

    void append_attendance(String json){
        db.insert("INSERT INTO pending(value) VALUES(?)", new String[]{json});
    }

    public long id() { return user_id;}

    public String name()
    {
        return user_name;
    }

    String email()
    {
        return user_email;
    }

    String password()
    {
        return user_pass;
    }

    User() {
        db = Database.getInstance();
        Cursor c = db.select("SELECT * FROM user WHERE id = 1;",null);
        c.moveToFirst();
        if(c.getCount() == 1 && c.getColumnCount() == 5 ) {
            user_id = c.getInt(1);
            user_name = c.getString(2);
            user_email = c.getString(3);
            user_pass = c.getString(4);
            Log.i("database", user_name + user_email + user_pass + c.getColumnCount());
        }
        c.close();
    }
}
