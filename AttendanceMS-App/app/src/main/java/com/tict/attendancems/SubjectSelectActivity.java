package com.tict.attendancems;

import android.content.Intent;
import android.database.Cursor;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.Toast;

import java.util.ArrayList;

public class SubjectSelectActivity extends AppCompatActivity {

    private ListView listView ;
    private ArrayAdapter<String> listAdapter ;
    ArrayList<String> refData;
    private int batch_no,batch_id;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_subject_select);

        listView = (ListView) findViewById(R.id.ss_listv);
        ArrayList<String> sb_list_al = new ArrayList<String>();
        listAdapter = new ArrayAdapter<String>(this,
                R.layout.ss_list_item, R.id.ss_list_txtv , sb_list_al);

        listView.setAdapter(listAdapter);
        listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                viewAttendanceSheet(i);
            }
        });
        getSupportActionBar().setSubtitle("Select Subject");

        init_data();
    }

    void init_data(){
        refData = new ArrayList<String>();
        batch_no = getIntent().getIntExtra("batch_no",-1);
        batch_id = getIntent().getIntExtra("batch_id",-1);
        if(batch_no == -1 || batch_id == -1 ) onBackPressed();
        Cursor c = Database.getInstance().select("SELECT code,name FROM subject " +
                "WHERE batch_id = ?; ",new String[]{String.valueOf(batch_id)});
        c.moveToFirst();
        for(int i = 0; i < c.getCount() ; ++i)
        {
            String code = c.getString(0);
            String name = c.getString(1);
            refData.add(code);
            listAdapter.add(code + "\n" + name);
            c.moveToNext();
        }
        c.close();
    }

    void viewAttendanceSheet(int i){
        finish();
        Intent intent = new Intent(this, AttendanceActivity.class);
        intent.putExtra("batch_no", batch_no );
        intent.putExtra("sub_code", refData.get(i) );
        startActivity(intent);
        //Toast.makeText(this," Selected " + i , Toast.LENGTH_SHORT).show();
    }

    public void onBackPressed() {
        finish();
        Intent intent = new Intent(this, BatchSelectActivity.class);
        startActivity(intent);
    }
}
