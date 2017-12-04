package com.tict.attendancems;

import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Color;
import android.graphics.Typeface;
import android.os.Build;
import android.support.v4.view.ViewCompat;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.Layout;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextClock;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Locale;
import java.util.Vector;

import static android.view.View.TEXT_ALIGNMENT_CENTER;


public class AttendanceActivity extends AppCompatActivity {

    private LinearLayout listView ;

    ArrayList<Integer> rolls  = new ArrayList<>();
    HashMap<Integer, StudnetAttn> refData = new HashMap<>();

    private int batch_no;
    private String sub_code;

    private EditText topic,mark,remarks;
    private EditText date;
    private Button submit;
    private ActionBar action;

    private int attn_count = 0;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_attendance);

        init_views();

    }

    void init_views(){
        listView = (LinearLayout) findViewById(R.id.as_listv);

        topic = (EditText) findViewById(R.id.as_topic);
        date = (EditText) findViewById(R.id.as_date);
        mark = (EditText) findViewById(R.id.as_mark);
        remarks = (EditText) findViewById(R.id.as_remarks);
        submit = (Button) findViewById(R.id.as_submit);
        submit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                sheetSubmit();
            }
        });

        String dateString = new SimpleDateFormat("yyyy-MM-dd").format(new Date());
        date.setText(dateString);
        mark.setText("1");

        init_data();
        ViewCompat.setNestedScrollingEnabled(listView, true);
        //listView.requestFocus();
        action = getSupportActionBar();
        action.setSubtitle("Attendance");
    }

    TextView get_tv(int i)
    {
        TextView tmptv = new TextView(this);
        tmptv.setTypeface(Typeface.DEFAULT_BOLD);
        tmptv.setPadding(10,30,10,30);
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            tmptv.setTextAppearance(R.style.TextAppearance_AppCompat_Medium);
        }
        tmptv.setOnClickListener(new AttnListner(tmptv,i) );
        return tmptv;
    }

    void init_data(){
        batch_no = getIntent().getIntExtra("batch_no",-1);
        sub_code = getIntent().getStringExtra("sub_code");
        if(batch_no == -1 || sub_code.length() < 1 ) onBackPressed();
        Cursor c = Database.getInstance().select("SELECT id,roll_no,name FROM student " +
                "WHERE batch_no = ?; ",new String[]{String.valueOf(batch_no)});
        c.moveToFirst();
        for(int i = 0; i < c.getCount() ; ++i)
        {
            int id = c.getInt(0);
            int roll = c.getInt(1);
            String name = c.getString(2);
            refData.put(roll,new StudnetAttn(id,false));
            rolls.add(roll);
            TextView tmptv = get_tv(i);
            tmptv.setText(roll +". " + name );
            listView.addView(tmptv);
            c.moveToNext();
        }
        c.close();
    }

    void inc_dec_counter(boolean b){
        if(b)attn_count++;
        else attn_count--;
        action.setSubtitle(String.valueOf(attn_count));
    }

    void markAttendance(View v, int i){
        int roll = rolls.get(i);
        //String str = listAdapter.getItem(i);
        if( refData.get(roll).attn )
        {
            refData.get(roll).attn = false;
            v.setBackgroundColor(Color.TRANSPARENT);
            ((TextView)v).setTextColor(Color.BLACK);
            inc_dec_counter(false);
            //adapterView.getChildAt(i).setBackgroundColor(Color.TRANSPARENT);
        }
        else
        {
            refData.get(roll).attn = true;
            ((TextView)v).setBackgroundColor(Color.parseColor("#ff4444"));
            ((TextView)v).setTextColor(Color.WHITE);
            inc_dec_counter(true);
            //adapterView.getChildAt(i).setBackgroundColor(Color.GREEN);
        }
        //Log.i("mytag",i + " roll : " + roll);
        //Toast.makeText(this," Selected " + i , Toast.LENGTH_SHORT).show();
    }

    void sheetSubmit()
    {
        String tpc = topic.getText().toString();
        if(tpc.length() < 1)
        {
            Toast.makeText(this," Topic Not Given " , Toast.LENGTH_SHORT).show();
            return;
        }
        String dte = date.getText().toString();
        if(dte.length() < 1)
        {
            Toast.makeText(this," Date Not Given " , Toast.LENGTH_SHORT).show();
            return;
        }
        String mrk = mark.getText().toString();
        if(mrk.length() < 1)
        {
            Toast.makeText(this," Attendance Mark Count Not Given " , Toast.LENGTH_SHORT).show();
            return;
        }
        String rmk = remarks.getText().toString();
        JSONArray jsn = new JSONArray();
        for(int i = 0 ; i< rolls.size() ; ++i)
        {
            int roll = rolls.get(i);
            JSONArray tmp = new JSONArray();
            if(refData.get(roll).attn){
                tmp.put(refData.get(roll).id);
                tmp.put(refData.get(roll).attn);
                jsn.put(tmp);
            }
        }
        JSONObject data = new JSONObject();
        try {
            data.put("teacher_id",User.getInstance().id());
            data.put("date",dte);
            data.put("attn",jsn);
            data.put("code",sub_code);
            data.put("topic",tpc);
            data.put("mark_count",mrk);
            data.put("remarks",rmk);
            data.put("batch_no",batch_no);

        } catch (JSONException e) {
            e.printStackTrace();
            Toast.makeText(this," JSON ERROR " + e.getMessage() , Toast.LENGTH_SHORT).show();
            return;
        }
        User.getInstance().append_attendance(data.toString());
        Toast.makeText(this," Attendance Saved " , Toast.LENGTH_SHORT).show();
        onBackPressed();
    }

    public void onBackPressed() {
        finish();
        Intent intent = new Intent(this, BatchSelectActivity.class);
        startActivity(intent);
    }

    class StudnetAttn {
        public int id;
        boolean attn;
        StudnetAttn(int id,boolean attn)
        {
            this.id = id;
            this.attn = attn;
        }
    }

    class AttnListner implements View.OnClickListener {

        private  TextView v;
        private  int i;

        AttnListner(TextView v , int i)
        {
            this.v = v;
            this.i = i;
        }

        @Override
        public void onClick(View view) {
            markAttendance(v,i);
        }
    }
}
