@echo off

REM Basic
set ANDROID_NDK_HOME=d:\DevTools\android-ndk-r8d
set PACKAGE_NAME=com.gameloft.android.ANMP.GloftIMHM
set MAIN_ACTIVITY=GL2JNIActivity

REM Advance
set PORT_NUMBER=12345
set TOOLCHAIN_VERSION=4.6

set GDB_SERVICE_NAME=MyService
set TMP_FILE=file.tmp

REM Delay in seconds between launching the activity and attaching gdbserver on it.
REM This is needed because there is no way to know when the activity has really
REM started, and sometimes this takes a few seconds.
set DELAY=2
set DELAY_EXTRA=4
