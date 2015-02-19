#include <gata/core/macro.h>

jint g_fd;
jlong g_startOffet;
int g_dataLength;

JNIEnv* g_JEnv = 0;

static jclass     gClassFileUtils;
static jmethodID  gMethodLoadFile;

FILE* my_fopen(const char* szFilePath, int* pDataLength)
{
	/*g_dataLength = -1;

	LOGI("my_fopen 1");
	
	jstring str = (*g_JEnv)->NewStringUTF(g_JEnv, szFilePath);
	LOGI("my_fopen 2");
	(*g_JEnv)->CallStaticVoidMethod(g_JEnv, gClassFileUtils, gMethodLoadFile, str);
	LOGI("my_fopen 3");
	(*g_JEnv)->DeleteLocalRef(g_JEnv, str);
	LOGI("my_fopen 4");
	
	if (g_dataLength == -1)
	{
		return NULL;
	}
	
	FILE* f = fdopen(g_fd, "rb");
    fseek(f, g_startOffet, SEEK_SET);
	
	*pDataLength = g_dataLength;*/

	char buffer[128];
	sprintf(buffer, "/sdcard/mario/%s", szFilePath);

	FILE* f = fopen(buffer, "rb");
	if (!f)
	{
		LOGI("ERROR: fail to load file [%s]", buffer);
		return NULL;
	}
	fseek(f, 0, SEEK_END);
	*pDataLength = ftell(f);
	fseek(f, 0, SEEK_SET);

	LOGI("OK: load file [%s] SUCCESS", buffer);
	return f;
}

JNIEXPORT void JNICALL Java_com_android_gl2jni_FileUtils_nativeLoadFileCallback(
		JNIEnv* env, void* reserved, jobject fileDescriptor, jlong offset, jlong length)
{
	LOGI("Java_com_android_gl2jni_FileUtils_nativeLoadFileCallback");
    jclass fdClass = (jclass)(*env)->FindClass(env, "java/io/FileDescriptor");
    jclass fdClassRef = (jclass)(*env)->NewGlobalRef(env, fdClass);
    jfieldID fdClassDescriptorFieldID = (*env)->GetFieldID(env, fdClass, "descriptor", "I");
    
	g_fd = (*env)->GetIntField(env, fileDescriptor, fdClassDescriptorFieldID);
	g_startOffet = offset;
	g_dataLength = length;
}

JNIEXPORT void JNICALL Java_com_android_gl2jni_FileUtils_nativeInit(JNIEnv* env, jclass thiz)
{
	LOGI("Java_com_android_gl2jni_FileUtils_nativeInit");
	g_JEnv = env;
	
	gClassFileUtils = (jclass)(*g_JEnv)->NewGlobalRef( g_JEnv, thiz );
	gMethodLoadFile = (*g_JEnv)->GetStaticMethodID( g_JEnv, gClassFileUtils, "loadFile", "(Ljava/lang/String;)V" );
}
