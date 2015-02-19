@echo off

call gdb_config.bat

adb version
if not "%ERRORLEVEL%"=="0" (
	echo ERROR: could not find ADB in your PATH variable.
	goto end
)

if "%1"=="auto" (
	adb shell am start -n %PACKAGE_NAME%/.%MAIN_ACTIVITY%
	adb shell sleep %DELAY%
)

set PID=
adb shell ps ^| grep %PACKAGE_NAME% > %TMP_FILE%
if not "%ERRORLEVEL%"=="0" (
	echo ERROR: ADB could not connect to device.
	goto end
)
set /P PID_ARR=< %TMP_FILE%
for /f "tokens=2" %%G in ("%PID_ARR%") do set PID=%%G

echo %PACKAGE_NAME% has PID = %PID%

if "%PID%"=="" (
	echo ERROR: That doesn't seem to be a running process. Please make sure your
    echo application has been started and that you are using the correct PACKAGE_NAME.
	goto end
)

adb forward tcp:%PORT_NUMBER% tcp:%PORT_NUMBER%

REM Check run-as
set USE_RUN_AS=0
adb shell run-as %PACKAGE_NAME% ls > NUL
REM if "%ERRORLEVEL%"=="0" set USE_RUN_AS=1

REM Check that there is no other instance of gdbserver running, otherwise kill it
set OLD_PID=0
adb shell ps ^| grep lib/gdbserver > %TMP_FILE%
set OLD_PID_ARR=
set /P OLD_PID_ARR=<%TMP_FILE%
echo OLD_PID_ARR=%OLD_PID_ARR%
for /f "tokens=2" %%G in ("%OLD_PID_ARR%") do set OLD_PID=%%G
echo OLD_PID=%OLD_PID%

if "%USE_RUN_AS%"=="1" (
	echo Use run-as to start the gdbserver!
	if not "%OLD_PID%"=="0" adb shell run-as %PACKAGE_NAME% kill %OLD_PID%
	adb shell run-as %PACKAGE_NAME% /data/data/%PACKAGE_NAME%/lib/gdbserver :%PORT_NUMBER% --attach %PID%
) else (
	echo Use Android Service to start the gdbserver, please make sure you implemented the service in your Java code!
	adb shell am startservice --user 0 -n %PACKAGE_NAME%/.%GDB_SERVICE_NAME% --ei PORT %PORT_NUMBER% --ei OLD_PID %OLD_PID%
)

:end
