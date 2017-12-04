package com.tict.attendancems;


import android.util.Log;

import java.io.BufferedOutputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.io.UnsupportedEncodingException;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;

/**
 * Created by SD on 11/21/2017.
 */

public class HttpHelper {
    private static final HttpHelper ourInstance = new HttpHelper();

    public static HttpHelper getInstance() {
        return ourInstance;
    }

    private HttpHelper() {
    }


    static String httpGet(String desiredUrl)
    {
        URL url;
        BufferedReader reader = null;
        StringBuilder stringBuilder;
        String exp;
        try
        {
            // create the HttpURLConnection
            url = new URL(desiredUrl);
            HttpURLConnection connection = (HttpURLConnection) url.openConnection();

            // just want to do an HTTP GET here
            connection.setRequestMethod("GET");

            // uncomment this if you want to write output to this url
            //connection.setDoOutput(true);

            // give it 15 seconds to respond
            connection.setReadTimeout(15*1000);
            connection.connect();
            int r = connection.getResponseCode();


            // read the output from the server
            reader = new BufferedReader(new InputStreamReader(connection.getInputStream()));
            stringBuilder = new StringBuilder();

            String line;
            while ((line = reader.readLine()) != null)
            {
                stringBuilder.append(line).append("\n");
            }
            return stringBuilder.toString().trim();
        }
        catch (Exception e)
        {
            e.printStackTrace();
            exp = e.getMessage();
        }
        finally
        {
            // close the reader; this can throw an exception too, so
            // wrap it in another try/catch block.
            if (reader != null)
            {
                try
                {
                    reader.close();
                }
                catch (IOException ioe)
                {
                    ioe.printStackTrace();
                }
            }
        }
        return "ERROR " +  exp;
    }

    static class httpGetRequest{

        private String desiredUrl = null;
        String errorMsg = null;
        boolean errorFlag = false;
        String data = "?app=true";

        httpGetRequest(String desiredUrl)
        {
            this.desiredUrl =  desiredUrl;
        }

        void putData(String key, String value)
        {
            if(!errorFlag) {
                try {
                    data += "&" + URLEncoder.encode(key, "UTF-8")
                            + "=" + URLEncoder.encode(value, "UTF-8");
                } catch (UnsupportedEncodingException e) {
                    e.printStackTrace();
                    errorFlag = true;
                    errorMsg = e.getMessage();
                }
            }
        }

        String post()
        {
            if(errorFlag){
                return errorMsg;
            }
            try {
                //Log.i("mytag",desiredUrl+data);
                return httpGet(desiredUrl+data);
            }
            catch (Exception e) {
                e.printStackTrace();
                errorFlag = true;
                errorMsg = e.getMessage();
            }
            return "ERROR " +  errorMsg;
        }

    }
}
