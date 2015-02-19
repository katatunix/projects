package APP_PACKAGE;

import java.io.InputStream;

import android.app.Service;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.IBinder;

import android.util.Log;

import java.io.IOException;
import java.util.Iterator;
import java.util.List;

class K {
	private static final String TAG = "TestService";
	public static void log(String log) {
		Log.d(TAG, log);
	}
}

public class MyService extends Service {
	
	private MyTask m_task;
	
	private final int LENGTH = 1024;
	private byte[] m_buffer = new byte[LENGTH];
	
	@Override
	public void onCreate() {
		super.onCreate();
		
		K.log("[MyService] onCreate");
		m_task = null;
	}
	
	@Override
	public void onDestroy() {
		super.onDestroy();
		K.log("[MyService] onDestroy");
	}
	
	@Override
	public IBinder onBind(Intent intent) {
		K.log("[MyService] onBind");
		return null;
	}
	
	@Override
	public int onStartCommand(Intent intent, int flags, int startId) {
		K.log("Received start id " + startId + ": " + intent);
		
		int ret = START_STICKY;
		
		if (intent == null) return ret;
		
		Bundle extras = intent.getExtras();
		if (extras == null) return ret;
		
		int port = extras.getInt("PORT", 0);
		K.log("PORT = " + port);
		if (port <= 0) return ret;
		
		int oldPid = extras.getInt("OLD_PID", 0);
		K.log("OLD_PID = " + oldPid);
		if (oldPid > 0) {
			K.log("Try to kill the old process of gdbserver (pid=" + oldPid + ")");
			android.os.Process.killProcess(oldPid);
		}
					
		if (m_task != null && m_task.isAlive()) {
			try {
				m_task.join();
			} catch (InterruptedException ex) {
				ex.printStackTrace();
			}
		}
		
		m_task = new MyTask(port);
		m_task.start();
		
		// We want this service to continue running until it is explicitly
		// stopped, so return sticky.
		return ret;
	}
	
	private class MyTask extends Thread {
		
		private int m_port;
		
		public MyTask(int port) {
			m_port = port;
		}

		@Override
		public void run() {
			try {
				Process process = new ProcessBuilder()
						.command(getFilesDir().getParent() + "/lib/gdbserver", "tcp:" + m_port,
							"--attach", android.os.Process.myPid() + "")
						.redirectErrorStream(true)
						.start();
				
				K.log("Begin process");
				
				InputStream in = process.getInputStream();
				
				while (true) {
					int read = in.read(m_buffer, 0, LENGTH);
					if (read <= 0) break;
					
					K.log(new String(m_buffer, 0, read));
				}
				
				in.close();
				process.destroy();
				
				K.log("End process");
				
			} catch (Exception ex) {
				ex.printStackTrace();
			}
		}
		
	}

}
