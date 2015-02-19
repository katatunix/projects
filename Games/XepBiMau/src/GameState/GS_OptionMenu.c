#include <vmsys.h>

#include "GS_OptionMenu.h"
#include "..\State.h"
#include "..\Game.h"
#include "..\Utils\Utils.h"
#include "..\Utils\FontBM.h"

#define OPTION_MENU_MAX_ITEM_NUMBER 2

extern VMINT g_TopStateStack;

extern VMCHAR TXT_SOUND_ON[];
extern VMCHAR TXT_SOUND_OFF[];
extern VMCHAR TXT_SHOW_NEXT_ON[];
extern VMCHAR TXT_SHOW_NEXT_OFF[];

VMINT optionMenuItemNumber;
VMCHAR** optionMenuItemTextList;
VMINT optionMenuItemEffect[OPTION_MENU_MAX_ITEM_NUMBER];
VMINT optionMenuCurHL;

VMINT isShowNext;
VMINT isSoundOn;

void pushOptionMenu()
{
	StateFuncs* curState;
	if (!isEmptyStateStack())
	{
		curState = getCurrentState();
		curState->leave();
	}
	
	g_TopStateStack++;
	curState = getCurrentState();
	curState->update = updateOptionMenu;
	curState->leave = leaveOptionMenu;
	curState->destroy = destroyOptionMenu;
	curState->resume = resumeOptionMenu;

	enterOptionMenu();
}

void enterOptionMenu()
{
	optionMenuItemNumber = 2;
	optionMenuItemTextList = (VMCHAR*) malloc(sizeof(VMCHAR*) * optionMenuItemNumber);
	optionMenuItemTextList[0] = isSoundOn ? TXT_SOUND_ON : TXT_SOUND_OFF;
	optionMenuItemTextList[1] = isShowNext ? TXT_SHOW_NEXT_ON : TXT_SHOW_NEXT_OFF;
	
	optionMenuCurHL = 0;

	startEffect(optionMenuItemNumber, optionMenuItemEffect);
}

void leaveOptionMenu()
{
}

void destroyOptionMenu()
{
	free(optionMenuItemTextList);
	optionMenuItemTextList = NULL;
}

void renderOptionMenu()
{
	renderMenu(optionMenuItemNumber, optionMenuItemTextList, optionMenuItemEffect, optionMenuCurHL);
	renderBackButton();
}

void updateOptionMenu()
{
	VMINT i;
	updateEffect(optionMenuItemNumber, optionMenuItemEffect);
	updateHL(optionMenuItemNumber, &optionMenuCurHL);

	if( isKeyDown( ACTION_KEY_OK ) )
	{
		i = optionMenuCurHL;
		if (optionMenuItemTextList[i] == TXT_SOUND_ON)
		{
			optionMenuItemTextList[i] = TXT_SOUND_OFF;
			stopMusic();
			isSoundOn = 0;
			return;
		}
		else if (optionMenuItemTextList[i] == TXT_SOUND_OFF)
		{
			playMusic();
			optionMenuItemTextList[i] = TXT_SOUND_ON;
			isSoundOn = 1;
			return;
		}
		else if (optionMenuItemTextList[i] == TXT_SHOW_NEXT_ON)
		{
			optionMenuItemTextList[i] = TXT_SHOW_NEXT_OFF;
			isShowNext = 0;
			return;
		}
		else if (optionMenuItemTextList[i] == TXT_SHOW_NEXT_OFF)
		{
			optionMenuItemTextList[i] = TXT_SHOW_NEXT_ON;
			isShowNext = 1;
			return;
		}
	}

	if( isKeyDown( ACTION_KEY_SOFTLEFT ) || isKeyDown( ACTION_KEY_CLEAR ) )
	{
		popState();
		return;
	}

	renderOptionMenu();
}

void resumeOptionMenu()
{
	startEffect(optionMenuItemNumber, optionMenuItemEffect);
}
