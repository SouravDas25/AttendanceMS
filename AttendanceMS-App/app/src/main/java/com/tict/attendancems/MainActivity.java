package com.tict.attendancems;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;

import java.security.spec.ECField;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Database.initInstance(this);

        User u = User.getInstance();
        Intent intent;
        Log.i("mytag","EMAIL "+ u.email());
        if( u.email().length() < 1 )
        {
            intent = new Intent(this, LoginActivity.class);
        }
        else
        {
            intent = new Intent(this, HomeActivity.class);
        }
        finish();
        startActivity(intent);
    }

}
