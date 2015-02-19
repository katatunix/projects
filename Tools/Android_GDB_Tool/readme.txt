Android GDB Tool

	nghia.buivan@gameloft.com - Autumn 2013

1. CONFIG:

- Open file gdb_config.bat by Notepad:
	+ Basically you need to specific the ANDROID_NDK_HOME and the PACKAGE_NAME.
	+ If you need to start the main activity of your application automatically before debugging session, please specific the MAIN_ACTIVITY (for example: GameActivity, GL2JNIActivity, ... etc.).

- Your application on the device must have the file: /data/data/PACKAGE_NAME/lib/gdbserver. In order to do that, follow these steps:

	+ Add an attribute called android:debuggable="true" to the <application> tag in your AndroidManifest.xml.
	+ Copy file ANDROID_NDK_HOME\prebuilt\android-arm\gdbserver\gdbserver to your "libs\armeabi-v7a" folder (similar for armeabi and x86).
	
- Copy your .so file to the "dev" folder. If you have .dsym file, just rename it to .so. The name of .so file must be matched the one which is on the device.

2. USE:

- Call run_server.bat to execute the gdbserver on device. Pass "auto" argument if you need, this will start your main activity before start the gdbserver.
Make sure the result is OK before run the client.

- Call run_client.bat and you should get the GDB prompt.

- You call run_all.bat [auto] to run server and then client (in this case, you may tweak the DELAY and DELAY_EXTRA in gdb_config.bat).

Refer to http://www.yolinux.com/TUTORIALS/GDB-Commands.html to learn about GDB Command Line.

<service android:name=".MyService" >
            <intent-filter>
                <action android:name=STR_APP_PACKAGE />
            </intent-filter>
        </service>