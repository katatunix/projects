#include <vmsys.h>

#include "GS_NewGameMenu.h"
#include "..\State.h"
#include "..\Game.h"
#include "..\Utils\Utils.h"
#include "..\Utils\FontBM.h"

#define NEW_GAME_MENU_MAX_ITEM_NUMBER 3

extern VMINT g_TopStateStack;
extern VMINT isLoadOldGame;
extern GameType curGameType;

extern VMCHAR TXT_LINE[];
extern VMCHAR TXT_SQUARE[];
extern VMCHAR TXT_BLOCK[];
extern VMCHAR TXT_BACK[];

VMINT newGameMenuItemNumber;
VMCHAR** newGameMenuItemTextList;
VMINT newGameMenuItemEffect[NEW_GAME_MENU_MAX_ITEM_NUMBER];
VMINT newGameMenuCurHL;

void pushNewGameMenu()
{
	StateFuncs* curState;
	if (!isEmptyStateStack())
	{
		curState = getCurrentState();
		curState->leave();
	}
	
	g_TopStateStack++;
	curState = getCurrentState();
	curState->update = updateNewGameMenu;
	curState->leave = leaveNewGameMenu;
	curState->destroy = destroyNewGameMenu;
	curState->resume = resumeNewGameMenu;

	enterNewGameMenu();
}

void enterNewGameMenu()
{
	newGameMenuItemNumber = 3;
	newGameMenuItemTextList = (VMCHAR*) malloc(sizeof(VMCHAR*) * newGameMenuItemNumber);
	newGameMenuItemTextList[0] = TXT_LINE;
	newGameMenuItemTextList[1] = TXT_SQUARE;
	newGameMenuItemTextList[2] = TXT_BLOCK;
	
	newGameMenuCurHL = 0;

	startEffect(newGameMenuItemNumber, newGameMenuItemEffect);
}

void leaveNewGameMenu()
{
}

void destroyNewGameMenu()
{
	free(newGameMenuItemTextList);
	newGameMenuItemTextList = NULL;
}

void renderNewGameMenu()
{
	renderMenu(newGameMenuItemNumber, newGameMenuItemTextList, newGameMenuItemEffect, newGameMenuCurHL);
	renderBackButton();
}

void updateNewGameMenu()
{
	VMINT i;
	updateEffect(newGameMenuItemNumber, newGameMenuItemEffect);
	updateHL(newGameMenuItemNumber, &newGameMenuCurHL);

	if( isKeyDown( ACTION_KEY_OK ) )
	{
		i = newGameMenuCurHL;
		if (newGameMenuItemTextList[i] == TXT_LINE)
		{
			isLoadOldGame = 0;
			curGameType = TYPE_LINE;
			popState();
			pushRun();
			return;
		}
		else if (newGameMenuItemTextList[i] == TXT_SQUARE)
		{
			isLoadOldGame = 0;
			curGameType = TYPE_SQUARE;
			popState();
			pushRun();
			return;
		}
		else if (newGameMenuItemTextList[i] == TXT_BLOCK)
		{
			isLoadOldGame = 0;
			curGameType = TYPE_BLOCK;
			popState();
			pushRun();
			return;
		}
	}

	if( isKeyDown( ACTION_KEY_SOFTLEFT ) || isKeyDown( ACTION_KEY_CLEAR ) )
	{
		popState();
		return;
	}

	renderNewGameMenu();
}

void resumeNewGameMenu()
{
	startEffect(newGameMenuItemNumber, newGameMenuItemEffect);
}
