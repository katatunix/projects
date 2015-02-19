/*
 * Copyright (C) 2009 The Android Open Source Project
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
/*
 * Copyright (C) 2008 The Android Open Source Project
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


import android.content.Context;
import android.graphics.PixelFormat;
import android.opengl.GLSurfaceView;
import android.util.AttributeSet;
import android.util.Log;
import android.view.KeyEvent;
import android.view.MotionEvent;

import javax.microedition.khronos.egl.EGL10;
import javax.microedition.khronos.egl.EGLConfig;
import javax.microedition.khronos.egl.EGLContext;
import javax.microedition.khronos.egl.EGLDisplay;
import javax.microedition.khronos.opengles.GL10;

/**
 * A simple GLSurfaceView sub-class that demonstrate how to perform
 * OpenGL ES 2.0 rendering into a GL Surface. Note the following important
 * details:
 *
 * - The class must use a custom context factory to enable 2.0 rendering.
 *   See ContextFactory class definition below.
 *
 * - The class must use a custom EGLConfigChooser to be able to select
 *   an EGLConfig that supports 2.0. This is done by providing a config
 *   specification to eglChooseConfig() that has the attribute
 *   EGL10.ELG_RENDERABLE_TYPE containing the EGL_OPENGL_ES2_BIT flag
 *   set. See ConfigChooser class definition below.
 *
 * - The class must select the surface's format, then choose an EGLConfig
 *   that matches it exactly (with regards to red/green/blue/alpha channels
 *   bit depths). Failure to do so would result in an EGL_BAD_MATCH error.
 */
class GL2JNIView extends GLSurfaceView {
	private static String TAG = "GAME";
	private static final boolean DEBUG = false;
	
	private boolean m_isLive = false;
	private boolean m_isPaused = false;
	
	public boolean isLive() {
		return m_isLive;
	}
	
	//=========================================================================================
	
	class IntSender implements Runnable {
		private boolean m_focus;
		public void set(boolean focus) {
			m_focus = focus;
		}
		public void run() { // called on OpenGL THREAD
			//Log.i(TAG, "GL thread - onWindowFocusChanged() focus=" + m_focus);
			if (!m_isLive) return;
			if (m_focus) {
				if (m_isPaused) {
					GL2JNILib.jniGameResume();
					m_isPaused = false;
				}
			} else {
				if (!m_isPaused) {
					GL2JNILib.jniGamePause();
					m_isPaused = true;
				}
			}
		}
	}
	
	private final static int INT_SENDERS_NUMBER = 64;
	private IntSender[] m_intSendersList = new IntSender[INT_SENDERS_NUMBER];
	private int m_intSenderCursor = 0;
	
	//=========================================================================================
	
	class DestroySender implements Runnable {
		public void run() { // called on OpenGL THREAD
			if (!m_isLive) return;
			GL2JNILib.jniGameDestroy();
			m_isLive = false;
		}
	}
	
	private final static int DESTROY_SENDERS_NUMBER = 4;
	private DestroySender[] m_destroySendersList = new DestroySender[DESTROY_SENDERS_NUMBER];
	private int m_destroySenderCursor = 0;
	
	//=========================================================================================

	public GL2JNIView(Context context) {
		super(context);
		init(false, 0, 0);
	}

	public GL2JNIView(Context context, boolean translucent, int depth, int stencil) {
		super(context);
		init(translucent, depth, stencil);
	}
	
	public void onWindowFocusChanged(boolean focus) { // called on UI THREAD
		if (!m_isLive) return;
		if (focus && !m_isPaused) return;
		if (!focus && m_isPaused) return;
		
		if (m_intSendersList[m_intSenderCursor] == null) {
			m_intSendersList[m_intSenderCursor] = new IntSender();
		}
		m_intSendersList[m_intSenderCursor].set(focus);
		queueEvent(m_intSendersList[m_intSenderCursor]);
		
		K.logi("[UI Thread] onWindowFocusChanged [begin] focus = " + focus);
		
		if (focus) {
			do { try {Thread.sleep(100);} catch (Exception ex) {} } while (m_isPaused);
		} else {
			do { try {Thread.sleep(100);} catch (Exception ex) {} } while (!m_isPaused);
		}
		
		K.logi("[UI Thread] onWindowFocusChanged [end] focus = " + focus);
		
		m_intSenderCursor++;
		if (m_intSenderCursor >= INT_SENDERS_NUMBER) {
			m_intSenderCursor = 0;
		}
	}
	
	public void onRequestDestroy() {
		if (!m_isLive) return;
		
		if (m_destroySendersList[m_destroySenderCursor] == null) {
			m_destroySendersList[m_destroySenderCursor] = new DestroySender();
		}
		queueEvent(m_destroySendersList[m_destroySenderCursor]);
		
		do {
			try {
				Thread.sleep(100);
			} catch (InterruptedException ex) {
				K.logi("error: " + ex.getMessage());
			}
		} while ( m_isLive );
		
		m_destroySenderCursor++;
		if (m_destroySenderCursor >= DESTROY_SENDERS_NUMBER) {
			m_destroySenderCursor = 0;
		}
	}

	private void init(boolean translucent, int depth, int stencil) {
	
		Log.i(TAG, "GL2JNIView.init()");
		
		GL2JNILib.jniGameCreated();
		
		m_isLive = true;

		/* By default, GLSurfaceView() creates a RGB_565 opaque surface.
		 * If we want a translucent one, we should change the surface's
		 * format here, using PixelFormat.TRANSLUCENT for GL Surfaces
		 * is interpreted as any 32-bit surface with alpha by SurfaceFlinger.
		 */
		if (translucent) {
			this.getHolder().setFormat(PixelFormat.TRANSLUCENT);
		}

		/* Setup the context factory for 2.0 rendering.
		 * See ContextFactory class definition below
		 */
		setEGLContextFactory(new ContextFactory());

		/* We need to choose an EGLConfig that matches the format of
		 * our surface exactly. This is going to be done in our
		 * custom config chooser. See ConfigChooser class definition
		 * below.
		 */
		setEGLConfigChooser( translucent ?
							 new ConfigChooser(8, 8, 8, 8, depth, stencil) :
							 new ConfigChooser(5, 6, 5, 0, depth, stencil) );
							
		

		/* Set the renderer responsible for frame rendering */
		setRenderer(new Renderer());
		
		setRenderMode(RENDERMODE_CONTINUOUSLY);
		//setDebugFlags( DEBUG_CHECK_GL_ERROR | DEBUG_LOG_GL_CALLS );
	}

	private static class ContextFactory implements GLSurfaceView.EGLContextFactory {
		private static int EGL_CONTEXT_CLIENT_VERSION = 0x3098;
		public EGLContext createContext(EGL10 egl, EGLDisplay display, EGLConfig eglConfig) {
			Log.w(TAG, "creating OpenGL ES 2.0 context");
			checkEglError("Before eglCreateContext", egl);
			int[] attrib_list = {EGL_CONTEXT_CLIENT_VERSION, 2, EGL10.EGL_NONE };
			EGLContext context = egl.eglCreateContext(display, eglConfig, EGL10.EGL_NO_CONTEXT, attrib_list);
			checkEglError("After eglCreateContext", egl);
			return context;
		}

		public void destroyContext(EGL10 egl, EGLDisplay display, EGLContext context) {
			egl.eglDestroyContext(display, context);
		}
	}

	private static void checkEglError(String prompt, EGL10 egl) {
		int error;
		while ((error = egl.eglGetError()) != EGL10.EGL_SUCCESS) {
			Log.e(TAG, String.format("%s: EGL error: 0x%x", prompt, error));
		}
	}

	private static class ConfigChooser implements GLSurfaceView.EGLConfigChooser {

		public ConfigChooser(int r, int g, int b, int a, int depth, int stencil) {
			mRedSize = r;
			mGreenSize = g;
			mBlueSize = b;
			mAlphaSize = a;
			mDepthSize = depth;
			mStencilSize = stencil;
		}

		/* This EGL config specification is used to specify 2.0 rendering.
		 * We use a minimum size of 4 bits for red/green/blue, but will
		 * perform actual matching in chooseConfig() below.
		 */
		private static int EGL_OPENGL_ES2_BIT = 4;
		private static int[] s_configAttribs2 =
		{
			EGL10.EGL_RED_SIZE, 4,
			EGL10.EGL_GREEN_SIZE, 4,
			EGL10.EGL_BLUE_SIZE, 4,
			EGL10.EGL_RENDERABLE_TYPE, EGL_OPENGL_ES2_BIT,
			EGL10.EGL_NONE
		};

		public EGLConfig chooseConfig(EGL10 egl, EGLDisplay display) {

			/* Get the number of minimally matching EGL configurations
			 */
			int[] num_config = new int[1];
			egl.eglChooseConfig(display, s_configAttribs2, null, 0, num_config);

			int numConfigs = num_config[0];

			if (numConfigs <= 0) {
				throw new IllegalArgumentException("No configs match configSpec");
			}

			/* Allocate then read the array of minimally matching EGL configs
			 */
			EGLConfig[] configs = new EGLConfig[numConfigs];
			egl.eglChooseConfig(display, s_configAttribs2, configs, numConfigs, num_config);

			if (DEBUG) {
				 printConfigs(egl, display, configs);
			}
			/* Now return the "best" one
			 */
			return chooseConfig(egl, display, configs);
		}

		public EGLConfig chooseConfig(EGL10 egl, EGLDisplay display,
				EGLConfig[] configs) {
			for(EGLConfig config : configs) {
				int d = findConfigAttrib(egl, display, config,
						EGL10.EGL_DEPTH_SIZE, 0);
				int s = findConfigAttrib(egl, display, config,
						EGL10.EGL_STENCIL_SIZE, 0);

				// We need at least mDepthSize and mStencilSize bits
				if (d < mDepthSize || s < mStencilSize)
					continue;

				// We want an *exact* match for red/green/blue/alpha
				int r = findConfigAttrib(egl, display, config,
						EGL10.EGL_RED_SIZE, 0);
				int g = findConfigAttrib(egl, display, config,
							EGL10.EGL_GREEN_SIZE, 0);
				int b = findConfigAttrib(egl, display, config,
							EGL10.EGL_BLUE_SIZE, 0);
				int a = findConfigAttrib(egl, display, config,
						EGL10.EGL_ALPHA_SIZE, 0);

				if (r == mRedSize && g == mGreenSize && b == mBlueSize && a == mAlphaSize)
					return config;
			}
			return null;
		}

		private int findConfigAttrib(EGL10 egl, EGLDisplay display,
				EGLConfig config, int attribute, int defaultValue) {

			if (egl.eglGetConfigAttrib(display, config, attribute, mValue)) {
				return mValue[0];
			}
			return defaultValue;
		}

		private void printConfigs(EGL10 egl, EGLDisplay display,
			EGLConfig[] configs) {
			int numConfigs = configs.length;
			Log.w(TAG, String.format("%d configurations", numConfigs));
			for (int i = 0; i < numConfigs; i++) {
				Log.w(TAG, String.format("Configuration %d:\n", i));
				printConfig(egl, display, configs[i]);
			}
		}

		private void printConfig(EGL10 egl, EGLDisplay display,
				EGLConfig config) {
			int[] attributes = {
					EGL10.EGL_BUFFER_SIZE,
					EGL10.EGL_ALPHA_SIZE,
					EGL10.EGL_BLUE_SIZE,
					EGL10.EGL_GREEN_SIZE,
					EGL10.EGL_RED_SIZE,
					EGL10.EGL_DEPTH_SIZE,
					EGL10.EGL_STENCIL_SIZE,
					EGL10.EGL_CONFIG_CAVEAT,
					EGL10.EGL_CONFIG_ID,
					EGL10.EGL_LEVEL,
					EGL10.EGL_MAX_PBUFFER_HEIGHT,
					EGL10.EGL_MAX_PBUFFER_PIXELS,
					EGL10.EGL_MAX_PBUFFER_WIDTH,
					EGL10.EGL_NATIVE_RENDERABLE,
					EGL10.EGL_NATIVE_VISUAL_ID,
					EGL10.EGL_NATIVE_VISUAL_TYPE,
					0x3030, // EGL10.EGL_PRESERVED_RESOURCES,
					EGL10.EGL_SAMPLES,
					EGL10.EGL_SAMPLE_BUFFERS,
					EGL10.EGL_SURFACE_TYPE,
					EGL10.EGL_TRANSPARENT_TYPE,
					EGL10.EGL_TRANSPARENT_RED_VALUE,
					EGL10.EGL_TRANSPARENT_GREEN_VALUE,
					EGL10.EGL_TRANSPARENT_BLUE_VALUE,
					0x3039, // EGL10.EGL_BIND_TO_TEXTURE_RGB,
					0x303A, // EGL10.EGL_BIND_TO_TEXTURE_RGBA,
					0x303B, // EGL10.EGL_MIN_SWAP_INTERVAL,
					0x303C, // EGL10.EGL_MAX_SWAP_INTERVAL,
					EGL10.EGL_LUMINANCE_SIZE,
					EGL10.EGL_ALPHA_MASK_SIZE,
					EGL10.EGL_COLOR_BUFFER_TYPE,
					EGL10.EGL_RENDERABLE_TYPE,
					0x3042 // EGL10.EGL_CONFORMANT
			};
			String[] names = {
					"EGL_BUFFER_SIZE",
					"EGL_ALPHA_SIZE",
					"EGL_BLUE_SIZE",
					"EGL_GREEN_SIZE",
					"EGL_RED_SIZE",
					"EGL_DEPTH_SIZE",
					"EGL_STENCIL_SIZE",
					"EGL_CONFIG_CAVEAT",
					"EGL_CONFIG_ID",
					"EGL_LEVEL",
					"EGL_MAX_PBUFFER_HEIGHT",
					"EGL_MAX_PBUFFER_PIXELS",
					"EGL_MAX_PBUFFER_WIDTH",
					"EGL_NATIVE_RENDERABLE",
					"EGL_NATIVE_VISUAL_ID",
					"EGL_NATIVE_VISUAL_TYPE",
					"EGL_PRESERVED_RESOURCES",
					"EGL_SAMPLES",
					"EGL_SAMPLE_BUFFERS",
					"EGL_SURFACE_TYPE",
					"EGL_TRANSPARENT_TYPE",
					"EGL_TRANSPARENT_RED_VALUE",
					"EGL_TRANSPARENT_GREEN_VALUE",
					"EGL_TRANSPARENT_BLUE_VALUE",
					"EGL_BIND_TO_TEXTURE_RGB",
					"EGL_BIND_TO_TEXTURE_RGBA",
					"EGL_MIN_SWAP_INTERVAL",
					"EGL_MAX_SWAP_INTERVAL",
					"EGL_LUMINANCE_SIZE",
					"EGL_ALPHA_MASK_SIZE",
					"EGL_COLOR_BUFFER_TYPE",
					"EGL_RENDERABLE_TYPE",
					"EGL_CONFORMANT"
			};
			int[] value = new int[1];
			for (int i = 0; i < attributes.length; i++) {
				int attribute = attributes[i];
				String name = names[i];
				if ( egl.eglGetConfigAttrib(display, config, attribute, value)) {
					Log.w(TAG, String.format("  %s: %d\n", name, value[0]));
				} else {
					// Log.w(TAG, String.format("  %s: failed\n", name));
					while (egl.eglGetError() != EGL10.EGL_SUCCESS);
				}
			}
		}

		// Subclasses can adjust these values:
		protected int mRedSize;
		protected int mGreenSize;
		protected int mBlueSize;
		protected int mAlphaSize;
		protected int mDepthSize;
		protected int mStencilSize;
		private int[] mValue = new int[1];
	}
	
	private static final boolean LIMIT_FPS = true;
	private static final int FRAMES_PER_SECOND = 25;
	private static final int SKIP_TICKS = 1000 / FRAMES_PER_SECOND;
	
	private long next_game_tick = 0;

	private class Renderer implements GLSurfaceView.Renderer {
		public void onDrawFrame(GL10 gl) { // called on OpenGL THREAD
			if (m_isPaused) return;
			
			if (next_game_tick == 0) {
				next_game_tick = System.currentTimeMillis();
			}
			
			GL2JNILib.jniGameLoop();
			
			if (LIMIT_FPS) {
				next_game_tick += SKIP_TICKS;
				
				int sleep_time = (int)( next_game_tick - System.currentTimeMillis() );
				if (sleep_time > 0) {
					try {
						Thread.sleep(sleep_time);
					} catch (InterruptedException ex) {
						K.loge("error: " + ex.getMessage());
					}
				} else {
					next_game_tick = System.currentTimeMillis();
				}
			}
		}

		public void onSurfaceChanged(GL10 gl, int width, int height) { // called on OpenGL THREAD
			Log.i(TAG, "onSurfaceChanged() width=" + width + ", height=" + height);
			GL2JNILib.jniSurfaceChanged(width, height);
		}

		public void onSurfaceCreated(GL10 gl, EGLConfig config) { // called on OpenGL THREAD
			Log.i(TAG, "*********** onSurfaceCreated()");
			GL2JNILib.jniSurfaceCreated();
		}
	}
	
	//=========================================================================================
	
	class TouchSender implements Runnable {
		private int m_a, m_x, m_y, m_id;
		public void set(int a, int x, int y, int id) {
			m_a = a; m_x = x; m_y = y; m_id = id;
		}
		public void run() { // called on OpenGL THREAD
			if (!m_isLive || m_isPaused) return;
			GL2JNILib.jniGameTouch(m_a, m_x, m_y, m_id);
		}
	}
	
	private final static int TOUCH_SENDERS_NUMBER = 64;
	private TouchSender[] m_touchSendersList = new TouchSender[TOUCH_SENDERS_NUMBER];
	private int m_touchSenderCursor = 0;
	
	private void putTouchToQueue(int a, int x, int y, int id) {
		if (m_touchSendersList[m_touchSenderCursor] == null) {
			m_touchSendersList[m_touchSenderCursor] = new TouchSender();
		}
		m_touchSendersList[m_touchSenderCursor].set( a, x, y, id );
		queueEvent(m_touchSendersList[m_touchSenderCursor]);
		m_touchSenderCursor++;
		if (m_touchSenderCursor >= TOUCH_SENDERS_NUMBER) {
			m_touchSenderCursor = 0;
		}
	}
	
	public boolean onTouchEvent(MotionEvent event) {
		final int DOWN_CODE = 0;
		final int UP_CODE = 1;
		
		int count = event.getPointerCount();
		int action = event.getAction();
		int actionCode = action & MotionEvent.ACTION_MASK;
		
		switch (actionCode) {
			case MotionEvent.ACTION_POINTER_UP: {
				int pointerUpIndex = action >> MotionEvent.ACTION_POINTER_ID_SHIFT;
				
				for (int i = 0; i < count; i++) {
					int x = (int)event.getX(i);
					int y = (int)event.getY(i);
					int pointerId = event.getPointerId(i);
					putTouchToQueue(i == pointerUpIndex? UP_CODE : DOWN_CODE, x, y, pointerId);
				}
				
				break;
			}
			
			case MotionEvent.ACTION_POINTER_DOWN:
			case MotionEvent.ACTION_DOWN:
			case MotionEvent.ACTION_MOVE: {
				for (int i = 0; i < count; i++) {
					int x = (int)event.getX(i);
					int y = (int)event.getY(i);
					int pointerId = event.getPointerId(i);
					putTouchToQueue(DOWN_CODE, x, y, pointerId);
				}
				break;
			}
			
			case MotionEvent.ACTION_UP:
			case MotionEvent.ACTION_CANCEL:
			case MotionEvent.ACTION_OUTSIDE: {
				for (int i = 0; i < count; i++) {
					int x = (int)event.getX(i);
					int y = (int)event.getY(i);
					int pointerId = event.getPointerId(i);
					putTouchToQueue(UP_CODE, x, y, pointerId);
				}
				break;
			}
		}
		
		//dumpEvent(event);
		
		return true;
	}
	
	/** Show an event in the LogCat view, for debugging */ 
	private void dumpEvent(MotionEvent event) {
		String names[] = { "DOWN" , "UP" , "MOVE" , "CANCEL" , "OUTSIDE" , "POINTER_DOWN" , "POINTER_UP" , "7?" , "8?" , "9?" };
		StringBuilder sb = new StringBuilder();
		int action = event.getAction();
		int actionCode = action & MotionEvent.ACTION_MASK;
		sb.append("event ACTION_" ).append(names[actionCode]);
		if (actionCode == MotionEvent.ACTION_POINTER_DOWN || actionCode == MotionEvent.ACTION_POINTER_UP) {
			sb.append("(pid " ).append( action >> MotionEvent.ACTION_POINTER_ID_SHIFT);
			sb.append(")" ); }
			sb.append("[" );
			for (int i = 0; i < event.getPointerCount(); i++) {
				sb.append("#" ).append(i);
				sb.append("(pid " ).append(event.getPointerId(i));
				sb.append(")=" ).append((int) event.getX(i));
				sb.append("," ).append((int) event.getY(i));
				if (i + 1 < event.getPointerCount()) sb.append(";" );
			}
			sb.append("]" ); Log.d(TAG, sb.toString());
	}
}
