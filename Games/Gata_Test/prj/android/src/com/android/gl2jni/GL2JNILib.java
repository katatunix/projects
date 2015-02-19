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

// Wrapper for native library

public class GL2JNILib {
	public static native void jniSurfaceCreated();
	public static native void jniSurfaceChanged(int width, int height);
	
	public static native void jniGameCreated();
	
	public static native void jniGameLoop();
	
	public static native void jniGameResume();
	public static native void jniGamePause();
	
	public static native void jniGameDestroy();
	
	public static native void jniGameTouch(int a, int x, int y, int id);
}
