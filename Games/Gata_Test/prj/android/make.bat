@echo off

call config.bat

set NDK_BUILD=%ANDROID_NDK_HOME%\ndk-build.cmd
set MY_BASH=%CYGWIN_BIN%\bash
set ANT_TOOL=%ANT_BIN%\ant

set MY_BACKUP=%CD%
set CYGWIN=nodosfilewarning

if "%1"=="pack" (
	%ANT_TOOL% debug
	
) else if "%1"=="install" (
	%ANT_TOOL% installd
	
) else if "%1"=="clean" (
	echo ========================================================
	echo Cleaning project: gata
	cd ..\..\..\Gata\prj\android
	if exist obj rd /s /q obj
	echo DONE
	
	echo ========================================================
	echo Cleaning project: gl2jni
	cd %MY_BACKUP%
	if exist obj rd /s /q obj
	if exist libs rd /s /q libs
	echo DONE
	
) else (
	echo ========================================================
	echo Compiling project: gata
	cd ..\..\..\Gata\prj\android
	%NDK_BUILD%
	echo DONE
	
	echo ========================================================
	echo Compiling project: gl2jni
	cd %MY_BACKUP%
	%NDK_BUILD%
	echo DONE
)
