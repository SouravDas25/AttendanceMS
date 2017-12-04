package com.tict.attendancems;

import android.content.Intent;
import android.database.sqlite.SQLiteDatabase;
import android.os.AsyncTask;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ListView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class SyncActivity extends AppCompatActivity {

    private ListView sync_list;
    private ArrayAdapter<String> listAdapter ;
    private Button sync_btn;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sync);

        sync_list = (ListView) findViewById(R.id.sync_list);
        ArrayList<String> sync_list_al = new ArrayList<String>();
        listAdapter = new ArrayAdapter<String>(this,
                R.layout.sync_list_item, R.id.txt_item , sync_list_al);

        sync_list.setAdapter(listAdapter);

        sync_btn = (Button) findViewById(R.id.sync_button);
        sync_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                sync_task();
            }
        });
    }

    @Override
    public void onBackPressed() {
        finish();
        Intent intent = new Intent(this, HomeActivity.class);
        startActivity(intent);
    }

    void sync_task()
    {
        UserSyncTask st = new UserSyncTask("/app/get_data_to_sync");
        st.execute();
    }

    public void afterSync() {
        finish();
        Intent intent = new Intent(this, BatchSelectActivity.class);
        startActivity(intent);
    }

    void completeSyncTask(JSONObject data)
    {
        if(data == null ) {
            listAdapter.add("Cannot Sync Json Error.");
            return ;
        }
        SQLiteDatabase db = Database.beginTransaction();
        try {
            JSONArray main_arr = data.getJSONArray("dept");
            int i;
            Dept.truncateDept(db);
            for (i = 0; i < main_arr.length(); ++i) {
                JSONObject tmp = main_arr.getJSONObject(i);
                Dept.insertDept(db,tmp);
            }
            listAdapter.add(i + " Departments Synced.");
            main_arr = data.getJSONArray("batch");
            Dept.truncateBatch(db);
            for (i = 0; i < main_arr.length(); ++i) {
                JSONObject tmp = main_arr.getJSONObject(i);
                Dept.insertBatch(db,tmp);
            }
            listAdapter.add(i + " Semester Synced.");
            main_arr = data.getJSONArray("subject");
            Subject.truncate(db);
            for (i = 0; i < main_arr.length(); ++i) {
                JSONObject tmp = main_arr.getJSONObject(i);
                Subject.insert(db,tmp);
            }
            listAdapter.add(i + " Subject Synced.");
            main_arr = data.getJSONArray("student_batch");
            Student.truncateStudentBatch(db);
            for (i = 0; i < main_arr.length(); ++i) {
                JSONObject tmp = main_arr.getJSONObject(i);
                Student.insertStudentBatch(db,tmp);
            }
            listAdapter.add(i + " batch Synced.");
            main_arr = data.getJSONArray("student");
            Student.truncateStudent(db);
            for (i = 0; i < main_arr.length(); ++i) {
                JSONObject tmp = main_arr.getJSONObject(i);
                Student.insertStudent(db,tmp);
            }
            listAdapter.add(i + " Student Synced.");
            Database.commit(db);
            return ;
        } catch (JSONException e) {
            e.printStackTrace();
            Database.rollback(db);
            listAdapter.add("Cannot Sync Json Error.");
            return ;
        }
        //listAdapter.add("APPLE " + listAdapter.getCount() );
    }

    public class UserSyncTask extends AsyncTask<Void, Void, Boolean> {

        private final String url;
        private JSONObject data;

        UserSyncTask(String durl) {
            url = durl;
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            // TODO: attempt authentication against a network service.

            String url = getString(R.string.website_link)+ this.url;
            HttpHelper.httpGetRequest request = new HttpHelper.httpGetRequest(url);
            String in = request.post();
            Log.i("mytag", String.valueOf(in.length()));
            try {
                data = new JSONObject(in);
            } catch (JSONException e) {
                e.printStackTrace();
                data = null;
                return false;
            }
            return true;
        }

        @Override
        protected void onPostExecute(final Boolean success) {
            if(success){
                completeSyncTask(data);
                sync_btn.setText("Continue");
                sync_btn.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        afterSync();
                    }
                });
            }
        }
    }

}
