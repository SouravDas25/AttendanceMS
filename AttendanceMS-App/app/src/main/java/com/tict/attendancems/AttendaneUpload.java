package com.tict.attendancems;

import android.app.Activity;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.support.v4.app.NotificationCompat;
import android.support.v4.app.NotificationCompat.Builder;
import android.util.Log;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by SD on 11/26/2017.
 */

class AttendaneUpload extends AsyncTask<Void, Void, Void> {

    private Context context;
    private ArrayList<Pending> all = null;

    public AttendaneUpload(Context context) {
        this.context = context;
    }

    public boolean has_pending()
    {
        if(all == null)get_all_pendings();
        return all.size() >= 1;
    }

    public ArrayList<Pending> get_all_pendings(){
        all = new ArrayList<>();
        Cursor c = Database.getInstance().select("SELECT * FROM pending ;",null);
        c.moveToFirst();
        for(int i =0 ;i < c.getCount();i++)
        {
            Pending p = new Pending(c.getInt(0),c.getString(1),c.getString(2));
            all.add(p);
            c.moveToNext();
        }
        c.close();
        return all;
    }

    public void upload_data(){
        if(all == null) get_all_pendings();
        for(Pending p : all)
        {
            AttendanceUploadTask task = new AttendanceUploadTask(p, (Activity) context);
            boolean b = task.do_upload();
            task.notify_user(b);
        }
    }

    @Override
    protected Void doInBackground(Void... voids) {
        upload_data();
        return (Void)null;
    }


    public class AttendanceUploadTask
    {

        private Pending pending;
        private String error = null;
        private Activity context;

        AttendanceUploadTask(Pending data,Activity context) {
            this.pending = data;
            this.context = context;
        }

        protected Boolean do_upload() {

            String url = "https://ticteduattendence.000webhostapp.com" +"/app/post_attn_data";
            HttpHelper.httpGetRequest request = new HttpHelper.httpGetRequest(url);
            request.putData("attn_data",pending.data);
            String data = request.post();
            Log.i("mytag",data);


            try {
                JSONObject obj = new JSONObject(data);
                if(obj.getBoolean("valid"))
                {
                    return true;
                }
                else
                {
                    error = obj.getString("err_des");
                    return false;
                }
            } catch (JSONException e) {
                e.printStackTrace();
                error = "Response Error. " + e.getMessage();
                return false;
            }
        }

        protected void notify_user(final Boolean success) {

            if(success)
            {
                SQLiteDatabase db = Database.beginTransaction();
                Database.query(db,"DELETE FROM pending WHERE id = ? ;", new Object [] {pending.id} );
                Database.commit(db);
                sendNotification("Attendance Uploaded Successfully.",
                        "Taken on " + pending.create_time);
                context.runOnUiThread(new MyRunner("Attendance Uploaded Successfully"));
            }
            else
            {
                sendNotification("Attendance Uploaded Error.",error);
                context.runOnUiThread(new MyRunner("Attendance Uploaded Error."));
            }
        }

        private void sendNotification(String title, String body) {
            Intent i = new Intent(context , MainActivity.class);
            i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            PendingIntent pi = PendingIntent.getActivity(context,
                    0 /* Request code */,
                    i,
                    PendingIntent.FLAG_ONE_SHOT);

            Uri sound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);

            NotificationCompat.Builder builder;
            builder = new Builder(context,context.getString(R.string.default_notification_channel_id));
            builder.setSmallIcon(R.mipmap.ic_launcher)
                    .setContentTitle(title)
                    .setContentText(body)
                    .setAutoCancel(true)
                    .setSound(sound)
                    .setContentIntent(pi);

            NotificationManager manager =
                    (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);

            manager.notify(0, builder.build());
        }
    }

    public class Pending {
        public int id;
        public String data;
        public String create_time;

        Pending(int id,String data,String create_time)
        {
            this.id = id;
            this.data = data;
            this.create_time = create_time;
        }


    }

    class MyRunner implements Runnable {
        private String data;
        MyRunner(String data){
            super();
            this.data = data;
        }

        public void run() {
            Toast.makeText(context,data,
                    Toast.LENGTH_SHORT).show();
        }
    }
}
