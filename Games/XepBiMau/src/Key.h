#ifndef __KEY_H__
#define __KEY_H__

#define ACTION_KEY_NONE			0
#define ACTION_KEY_LEFT			1
#define ACTION_KEY_RIGHT		2
#define ACTION_KEY_UP			4
#define ACTION_KEY_DOWN			8
#define ACTION_KEY_5			32
#define ACTION_KEY_1			64
#define ACTION_KEY_2			128
#define ACTION_KEY_3			256
#define ACTION_KEY_7			512
#define ACTION_KEY_8			1024
#define ACTION_KEY_9			2048
#define ACTION_KEY_SOFTLEFT		4096
#define ACTION_KEY_SOFTRIGHT	8192
#define ACTION_KEY_0			16384
#define ACTION_KEY_OK			32768
#define ACTION_KEY_CLEAR		65536

void resetKey();
void resetKeyPressedState();
void onKeyDown( VMINT keycode );
void onKeyUp( VMINT keycode );
void onKeyRepeat( VMINT keycode );

int isKeyDown( VMINT action );
int isKeyRepeated( VMINT action );

#endif // __KEY_H__
