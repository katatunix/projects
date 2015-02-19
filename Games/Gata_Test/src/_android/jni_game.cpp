/*
 * Copyright (C) 2009 The Android Open Source Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// OpenGL ES 2.0 code

#include <gata/core/macro.h>

#include "../CGame.h"

extern "C" {
    JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniSurfaceCreated(JNIEnv * env, jobject obj);
	JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniSurfaceChanged(JNIEnv * env, jobject obj, jint width, jint height);
	
	JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniGameCreated(JNIEnv * env, jobject obj);
	
    JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniGameLoop(JNIEnv * env, jobject obj);
	
	JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniGamePause(JNIEnv * env, jobject obj);
	JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniGameResume(JNIEnv * env, jobject obj);
	JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniGameDestroy(JNIEnv * env, jobject obj);

	JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniGameTouch(JNIEnv * env, jobject obj, jint a, jint x, jint y, jint id);
}

JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniSurfaceCreated(JNIEnv * env, jobject obj)
{
	LOGI("-------jniSurfaceCreated");
    g_pGraphic->init(0);
	g_pGraphic->setClearColor(GAME_CLEAR_COLOR);
}

JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniSurfaceChanged(JNIEnv * env, jobject obj,  jint width, jint height)
{
	LOGI("--------jniSurfaceChanged width=%d height=%d", width, height);
	g_pGame->resize( width, height );
}

JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniGameCreated(JNIEnv * env, jobject obj)
{
    new CGame();
}



JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniGameLoop(JNIEnv * env, jobject obj)
{
    g_pGame->loop();
#if 0
	void KPSwapBuffers();
	KPSwapBuffers();
#endif
}

JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniGamePause(JNIEnv * env, jobject obj)
{
    LOGI("jniGamePause");
	g_pGame->pause();
}

JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniGameResume(JNIEnv * env, jobject obj)
{
    LOGI("jniGameResume");
	g_pGame->resume();
}

JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniGameDestroy(JNIEnv * env, jobject obj)
{
    LOGI("jniGameDestroy");
	CGame::freeInstance();
}

JNIEXPORT void JNICALL Java_com_android_gl2jni_GL2JNILib_jniGameTouch(JNIEnv * env, jobject obj, jint a, jint x, jint y, jint id)
{
	if (a == 0)
		g_pGame->onMouseDown(x, y, id);
	else
		g_pGame->onMouseUp(x, y, id);
}
