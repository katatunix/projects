@echo off

call gdb_config.bat

start run_server %1
if not "%1"=="" (
	set /A DELAY += %DELAY_EXTRA%
)

echo DELAY=%DELAY%

sleep %DELAY%

call run_client
