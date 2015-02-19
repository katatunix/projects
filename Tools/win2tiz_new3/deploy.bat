@echo off

set BUILD_PATH=%~dp0\build\win2tiz\
del /q %BUILD_PATH%\*.exe
del /q %BUILD_PATH%\*.dll

cd win2tiz\bin\x86\Release
copy win2tiz.exe %BUILD_PATH%
copy *.dll %BUILD_PATH%
cd ..\..\..\..
cd mongcc\bin\x86\Release
copy mongcc.exe %BUILD_PATH%
cd ..\..\..\..

pause
