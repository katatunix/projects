@echo off

call gdb_config.bat

adb forward tcp:%PORT_NUMBER% tcp:%PORT_NUMBER%

adb pull /system/bin/app_process app_process
adb pull /system/bin/linker linker
adb pull /system/lib/libc.so libc.so

set GDB_CLIENT=%ANDROID_NDK_HOME%\toolchains\arm-linux-androideabi-%TOOLCHAIN_VERSION%\prebuilt\windows\bin\arm-linux-androideabi-gdb.exe

(
	echo set solib-search-path dev
	echo file app_process
	echo target remote :%PORT_NUMBER%
) > %TMP_FILE%

%GDB_CLIENT% -x %TMP_FILE%
