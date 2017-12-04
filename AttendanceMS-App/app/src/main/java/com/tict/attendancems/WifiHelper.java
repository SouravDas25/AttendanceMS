package com.tict.attendancems;

import android.app.Activity;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.net.wifi.ScanResult;
import android.net.wifi.WifiManager;

import java.util.List;

/**
 * Created by SD on 11/23/2017.
 */

public class WifiHelper {

    private WifiManager mainWifi;

    WifiHelper(Activity context) {
        mainWifi = (WifiManager) context.getApplicationContext()
                .getSystemService(Context.WIFI_SERVICE);

        WifiScanReceiver wifiReciever = new WifiScanReceiver();
        context.getApplicationContext().registerReceiver(wifiReciever,
                new IntentFilter(WifiManager.SCAN_RESULTS_AVAILABLE_ACTION));
    }

    boolean scan() {
        return mainWifi.startScan();
    }

    List<ScanResult> scanResultList() {
        return mainWifi.getScanResults();
    }

    class WifiScanReceiver extends BroadcastReceiver {
        public void onReceive(Context c, Intent intent) {
        }
    }
}
