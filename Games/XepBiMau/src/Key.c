#include <vmio.h>
#include "Key.h"

VMUINT keyMask;
VMUINT keyRepeatedMask;

VMUINT8 charKey[VM_KEY_z + 1];

VMINT isCharKey(VMINT keycode)
{
	return	(VM_KEY_A <= keycode && keycode <= VM_KEY_Z) ||
			(VM_KEY_a <= keycode && keycode <= VM_KEY_z) ||
			(keycode == VM_KEY_SPACE);
}

void resetKey()
{
	keyMask = 0;
	keyRepeatedMask = 0;
	memset(charKey, 0, sizeof(VMUINT8) * (VM_KEY_z + 1));
}

int mapKeyToAction( VMINT keycode )
{
	switch( keycode )
	{
	case VM_KEY_LEFT:
		return ACTION_KEY_LEFT;
	case VM_KEY_RIGHT:
		return ACTION_KEY_RIGHT;
	case VM_KEY_UP:
		return ACTION_KEY_UP;
	case VM_KEY_DOWN:
		return ACTION_KEY_DOWN;
	case VM_KEY_NUM4:
		return ACTION_KEY_LEFT;
	case VM_KEY_NUM5:
		return ACTION_KEY_5;
	case VM_KEY_NUM6:
		return ACTION_KEY_RIGHT;
	case VM_KEY_NUM1:
		return ACTION_KEY_1;
	case VM_KEY_NUM2:
		return ACTION_KEY_2;
	case VM_KEY_NUM3:
		return ACTION_KEY_3;
	case VM_KEY_NUM7:
		return ACTION_KEY_7;
	case VM_KEY_NUM8:
		return ACTION_KEY_8;
	case VM_KEY_NUM9:
		return ACTION_KEY_9;
	case VM_KEY_LEFT_SOFTKEY:
		return ACTION_KEY_SOFTLEFT;
	case VM_KEY_RIGHT_SOFTKEY:
		return ACTION_KEY_SOFTRIGHT;
	case VM_KEY_NUM0:
		return ACTION_KEY_0;
	case VM_KEY_OK:
	case VM_KEY_ENTER:
		return ACTION_KEY_OK;
	case VM_KEY_CLEAR:
		return ACTION_KEY_CLEAR;
		break;
	}

	return ACTION_KEY_NONE;
}

void onKeyDown( VMINT keycode )
{
	keyMask |= mapKeyToAction( keycode );

	if (isCharKey(keycode))
	{
		charKey[keycode] = 1;
	}
}

void onKeyRepeat( VMINT keycode )
{
	keyRepeatedMask |= mapKeyToAction( keycode );
}

void onKeyUp( VMINT keycode )
{
	int k = mapKeyToAction( keycode );
	keyMask				&= ~k;
	keyRepeatedMask		&= ~k;
}

int isKeyDown( VMINT action )
{
	int b = ( keyMask & action );
	if (b)
	{
		keyMask &= ~action;
	}
	return b;
}

int isKeyRepeated( VMINT action )
{
	return ( keyRepeatedMask & action );
}
