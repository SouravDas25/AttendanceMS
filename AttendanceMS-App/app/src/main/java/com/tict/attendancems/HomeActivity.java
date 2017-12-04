package com.tict.attendancems;

import android.content.Intent;
import android.os.AsyncTask;
import android.support.constraint.ConstraintLayout;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

public class HomeActivity extends AppCompatActivity {

    private AttendaneUpload au;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        boolean frm_login = getIntent().getBooleanExtra("frm_login",false);
        if(frm_login){
            init_welcome();
        }
        else {
            init_actual();
        }
        au = new AttendaneUpload(this);
        if(au.has_pending())
        {
            au.execute();
            Toast.makeText(this,"Uploading Pending Attendance Records",
                    Toast.LENGTH_SHORT).show();
        }
    }

    void init_welcome(){
        setContentView(R.layout.welcome_activity);
        TextView username = (TextView)findViewById(R.id.wp_user_name);
        TextView email = (TextView)findViewById(R.id.wp_email);
        username.setText(User.getInstance().name());
        email.setText(User.getInstance().email());
        ConstraintLayout cl = (ConstraintLayout)findViewById(R.id.wp_btn);
        cl.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                init_actual();
            }
        });
    }

    void init_actual(){
        setContentView(R.layout.activity_home);

        ImageButton online = (ImageButton) findViewById(R.id.online);
        ImageButton offline = (ImageButton) findViewById(R.id.offline);
        ImageButton sync = (ImageButton) findViewById(R.id.hp_btn_sync);
        final ImageButton logout = (ImageButton) findViewById(R.id.hp_btn_logout);
        online.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                init_onlineApp();
            }
        });
        offline.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                init_offlineApp();
            }
        });
        sync.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                init_sync();
            }
        });
        logout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                logout();
            }
        });
    }

    public void init_onlineApp()
    {
        finish();
        Intent intent = new Intent(this, WebActivity.class);
        startActivity(intent);
    }

    public void init_offlineApp()
    {
        finish();
        Intent intent = new Intent(this, BatchSelectActivity.class);
        startActivity(intent);
    }

    public void init_sync()
    {
        finish();
        Intent intent = new Intent(this, SyncActivity.class);
        startActivity(intent);
    }

    public void logout()
    {
        User.getInstance().logout();
        finish();
        Intent intent = new Intent(this, MainActivity.class);
        startActivity(intent);
    }

}
