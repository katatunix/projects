#include <vmsys.h>

#include "GS_AskSoundMenu.h"
#include "..\State.h"
#include "..\Game.h"
#include "..\Utils\Utils.h"
#include "..\Utils\FontBM.h"

extern VMINT g_TopStateStack;

extern VMCHAR TXT_ASK_SOUND[];
extern VMCHAR TXT_YES[];
extern VMCHAR TXT_NO[];

extern VMINT isSoundOn;

void pushAskSoundMenu()
{
	StateFuncs* curState;
	if (!isEmptyStateStack())
	{
		curState = getCurrentState();
		curState->leave();
	}
	
	g_TopStateStack++;
	curState = getCurrentState();
	curState->update = updateAskSoundMenu;
	curState->leave = leaveAskSoundMenu;
	curState->destroy = destroyAskSoundMenu;
	curState->resume = resumeAskSoundMenu;

	enterAskSoundMenu();
}

void enterAskSoundMenu()
{
}

void leaveAskSoundMenu()
{
}

void destroyAskSoundMenu()
{
}

void renderAskSoundMenu()
{
	vm_graphic_fill_rect(g_ScreenBuffer, 0, 0, SCREEN_WIDTH, SCREEN_HEIGHT,
			MENU_BGR_COLOR, MENU_BGR_COLOR);
	fontBMDrawString(FONT_LARGE, TXT_ASK_SOUND, SCREEN_WIDTH >> 1, SCREEN_HEIGHT >> 1,
			HCENTER | VCENTER);
	fontBMDrawString(FONT_MEDIUM, TXT_YES, 6, 232, LEFT | BOTTOM);
	fontBMDrawString(FONT_MEDIUM, TXT_NO, 315, 232, RIGHT | BOTTOM);
}

void updateAskSoundMenu()
{
	if( isKeyDown( ACTION_KEY_SOFTLEFT ) || isKeyDown( ACTION_KEY_OK ) ) // YES
	{
		isSoundOn = 1;
		playMusic();
		pushMainMenu();
		return;
	}
	if( isKeyDown( ACTION_KEY_SOFTRIGHT ) ) // NO
	{
		isSoundOn = 0;
		pushMainMenu();
		return;
	}

	renderAskSoundMenu();
}

void resumeAskSoundMenu()
{
}
