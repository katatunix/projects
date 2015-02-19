=================================================================================================
=================================================================================================
Win2Tiz (c) nghia.buivan@gameloft.com, Summer 2014 - FIFA World Cup Brazil
(Since Spring 2013)

Client usage: Win2Tiz.exe -i <str> [-t <str>] [-p <str>] [-g <str>] [-v] [-j <num>]
  -i      input path/filename. (Ex: Win2Tiz.xml)
  -t      type of build <release|debug>
  -p      <project name> to build or <all>
  -g      the gcc config <GccConfig> </GccConfig> choosed from Win2Tiz.xml
  -v      verbose. Print a lot of info.
  -j      jobs or how many simultaneous processes.
  -o      output path name.
Server usage: Win2Tiz.exe --server [-port <port>] [-backlog <backlog>] [-tempdir <tempdir>]
  -port      port number to listen. Default: 1909.
  -backlog   max number of clients that server can handle at the same time. Default: 4.
  -tempdir   path to the folder that server can write temporary data. This path should be short. Default: working dir.
  
=================================================================================================
=================================================================================================

* This tool's usage and behavior is similar to sln2gcc. Just replace your sln2gcc.exe by Win2Tiz.exe.

* How to use mongcc:

- In server computer:
	+ Make sure you have enough hard disk space (4 GB is good).
	+ Start the server: Win2Tiz.exe --server -port 1909 -backlog 4 -tempdir e:\x

- In client computer, config for the sln2gcc.xml or Win2Tiz.xml like this:
	<GccConfig Name="armeabi-v7a">
		<Macro Name="USE_MONGCC" 						Value="true" />
		<Macro Name="MONGCC_SERVERS" 					Value="sa2wks0043 sa2wks0047 sa2wks0091" />
		<Macro Name="MONGCC_NEEDED_SERVERS_NUMBER" 		Value="2" />
		<Macro Name="MONGCC_PORT" 						Value="1909" />
		<Macro Name="MONGCC_TIMEOUT" 					Value="180000" />
	+ USE_MONGCC: true/false or 1/0, enable/disable mongcc feature, if this is disabled, other mongcc macros
		will be ignored.
	+ MONGCC_SERVERS: list of server computer name
	+ MONGCC_NEEDED_SERVERS_NUMBER: number of needed servers number.
		For example, you can define many computers in MONGCC_SERVERS;
		but only MONGCC_NEEDED_SERVERS_NUMBER successful servers will be used.
		You can define MONGCC_NEEDED_SERVERS_NUMBER=0 or remove it to use all servers in MONGCC_SERVERS (maximum).
		If MONGCC_NEEDED_SERVERS_NUMBER is greater than the computers number in MONGCC_SERVERS,
		we will use all servers in MONGCC_SERVERS (maximum).
	+ MONGCC_PORT: the TCP port, should be equal to the -port parameter when starting Win2Tiz server (see "Server usage" above).
	+ MONGCC_TIMEOUT: (millisecond) when client requests server to compile a source file,
		client will wait for reponse from server;
		after MONGCC_TIMEOUT and nothing is received, client will mark the request and connection is failed.

- In both server and client, make sure the path to Android NDK is the same.
	You should map the DevTools folder to T:\ so we have ANDROID_NDK_HOME=T:\android-ndk-r9d for example.
	
- To map a folder to a drive, use subst DOS command, for example:
	+ Map current folder to drive T:
		subst T: .
	+ Map the folder e:\DevTools to drive T:
		subst T: e:\DevTools