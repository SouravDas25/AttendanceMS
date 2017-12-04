package com.tict.attendancems;

import android.content.Intent;
import android.database.Cursor;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ListView;
import android.widget.Toast;

import java.util.ArrayList;

public class BatchSelectActivity extends AppCompatActivity {

    private ListView listView ;
    private ArrayAdapter<String> listAdapter ;
    ArrayList<Integer[]> refData;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if( ! isSynced() ){
            init_not_synced_view();
        }
        else{
            init_synced_view();
        }

    }

    void init_not_synced_view(){
        setContentView(R.layout.please_sync);
        Button btn = (Button) findViewById(R.id.star_sync);
        btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
                Intent intent = new Intent(BatchSelectActivity.this, SyncActivity.class);
                startActivity(intent);
            }
        });
    }

    void init_synced_view(){
        setContentView(R.layout.activity_batch_select);

        listView = (ListView) findViewById(R.id.sb_listv);
        ArrayList<String> sb_list_al = new ArrayList<String>();
        listAdapter = new ArrayAdapter<String>(this,
                R.layout.sb_list_item, R.id.sb_list_txtv , sb_list_al);

        listView.setAdapter(listAdapter);
        listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                viewSubjectList(i);
            }
        });
        getSupportActionBar().setSubtitle("Select Batch");
        init_data();
    }

    boolean isSynced(){
        Cursor c = Database.getInstance().select("SELECT * FROM dept",null);
        boolean b = true;
        if(c.getCount() < 1){
            b = false;
        }
        c.close();
        return b;
    }

    void init_data(){
        refData = new ArrayList<Integer[]>();
        Cursor c = Database.getInstance().select("SELECT dept_name ," +
                " sem_no , current_year , batch_no , batch_id" +
                " FROM batch_view ORDER BY dept_name , current_year ;",null);
        c.moveToFirst();
        for(int i = 0; i < c.getCount() ; ++i)
        {
            String dept_name = c.getString(0);
            int sem_no = c.getInt(1);
            int year = c.getInt(2);
            int batch_no = c.getInt(3);
            int batch_id = c.getInt(4);
            refData.add( new Integer[] {batch_no,batch_id} );
            String val = dept_name + "\n" + ordinal(year) + " Year " + ordinal(sem_no) + " Semester ";
            listAdapter.add(val);
            c.moveToNext();
        }
        c.close();
    }

    void viewSubjectList(int i){
        finish();
        Intent intent = new Intent(this, SubjectSelectActivity.class);
        intent.putExtra("batch_no", refData.get(i)[0].intValue() );
        intent.putExtra("batch_id", refData.get(i)[1].intValue() );
        startActivity(intent);
        //Toast.makeText(this," Selected " + i , Toast.LENGTH_SHORT).show();
    }

    public void onBackPressed() {
        finish();
        Intent intent = new Intent(this, HomeActivity.class);
        startActivity(intent);
    }

    public static String ordinal(int i) {
        int mod100 = i % 100;
        int mod10 = i % 10;
        if(mod10 == 1 && mod100 != 11) {
            return i + "st";
        } else if(mod10 == 2 && mod100 != 12) {
            return i + "nd";
        } else if(mod10 == 3 && mod100 != 13) {
            return i + "rd";
        } else {
            return i + "th";
        }
    }
}
