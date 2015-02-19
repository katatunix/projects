/*
 * Copyright (C) 2007 The Android Open Source Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *	  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

package com.android.gl2jni;

import android.app.Activity;
import android.os.Bundle;
import android.util.Log;
import android.view.WindowManager;
import android.content.pm.ActivityInfo;

//import java.io.File;

import java.net.*;
import java.io.*;


public class GL2JNIActivity extends Activity {

	GL2JNIView mView;
	public static String TAG = "GAME";

	@Override protected void onCreate(Bundle icicle) {
		super.onCreate(icicle);
		K.logi("GL2JNIActivity onCreate()");
		this.setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_LANDSCAPE);
		
		System.loadLibrary("gl2jni");
		FileUtils.init( getApplicationContext() );
		
		mView = new GL2JNIView(getApplication());
		setContentView(mView);
		
		// ServerSocket server = null;
		// Socket client = null;
		
		// try {
			// server = new ServerSocket(38300);
			// server.setSoTimeout(60 * 1000);
			// K.logd("Now waiting for client...");
			// client = server.accept();
			// K.logd("A client connected!");
		// } catch (SocketTimeoutException ex) {
			// K.logd("Timeout!!!");
		// } catch (Exception ex) {
			// K.loge(ex.getMessage());
		// } finally {
			// //close the server socket
			// try {
				// if (server != null)
					// server.close();
			// } catch (IOException ec) {
				// K.loge("Cannot close server socket" + ec);
			// }
		// }
	}
	
	@Override protected void onStart() {
		super.onStart();
		//Log.i(TAG, "onStart()");
	}
	
	@Override protected void onStop() {
		super.onStop();
		//Log.i(TAG, "onStop()");
	}

	@Override protected void onPause() {
		super.onPause();
		//mView.onPause();
		K.logi("GL2JNIActivity onPause()");
	}

	@Override protected void onResume() {
		super.onResume();
		//mView.onResume();
		K.logi("GL2JNIActivity onResume()");
	}
	
	@Override protected void onDestroy() {
		super.onDestroy();
		K.logi("onDestroy() [begin]");
		mView.onRequestDestroy();
		
		System.exit(0);
		K.logi("onDestroy() [end]");
	}
}
