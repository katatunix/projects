package com.android.gl2jni;

import java.io.FileDescriptor;

import android.content.res.AssetFileDescriptor;
//import android.content.res.AssetManager
import android.content.res.Resources;

import android.content.Context;

public class FileUtils {

	private static Context m_sContext;
	
	public static void init(Context context) {
		m_sContext = context;
		nativeInit();
	}

	public static void loadFile(String filePath) {
		K.logi("java loadFIle 1 filePath=" + filePath);
		Resources res = m_sContext.getResources();
		K.logi("java loadFIle 2");
		try
		{
			String basename = filePath;
			int index = filePath.lastIndexOf('.');
			if (index > -1) {
				basename = filePath.substring(0, index);
			}
			K.logi("basename = " + basename);
			
			
			int rID = res.getIdentifier("com.android.gl2jni:raw/"+basename, null, null); 
			AssetFileDescriptor descriptor = res.openRawResourceFd(rID);
			K.logi("java loadFIle 3");
			nativeLoadFileCallback( descriptor.getFileDescriptor(), descriptor.getStartOffset(), descriptor.getLength() );
			K.logi("java loadFIle 4");
			K.logi("loadFile(" + filePath + ") SUCCESS");
		}
		catch (Exception ex)
		{
			K.logi("LoadFile(" + filePath + ") ERROR: " + ex.getMessage());
		}
	}
 
	public static native void nativeLoadFileCallback(FileDescriptor fileDescriptor, long offset, long length);
	public static native void nativeInit();
}
