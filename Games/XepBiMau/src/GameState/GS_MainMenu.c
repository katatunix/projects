#include <vmsys.h>
#include <time.h>

#include "GS_MainMenu.h"
#include "..\State.h"
#include "..\Game.h"
#include "..\Utils\Utils.h"
#include "..\Utils\FontBM.h"

#define MAIN_MENU_MAX_ITEM_NUMBER 7

extern VMINT g_TopStateStack;

extern VMINT isLoadOldGame; // From GS_Run

extern VMCHAR TXT_CONTINUE[];
extern VMCHAR TXT_NEW_GAME[];
extern VMCHAR TXT_OPTION[];
extern VMCHAR TXT_HIGH_SCORE[];
extern VMCHAR TXT_HELP[];
extern VMCHAR TXT_EXIT[];

extern VMCHAR TXT_BACK[];

VMINT mainMenuItemNumber;
VMCHAR** mainMenuItemTextList;
VMINT mainMenuItemEffect[MAIN_MENU_MAX_ITEM_NUMBER];
VMINT mainMenuCurHL;

VMINT* aLoad;
VMINT scoreLoad;
GameType typeLoad;
// Bubble
#define BUBBLE_COLOR	VM_COLOR_888_TO_565(32, 180, 200)
#define BUBBLE_NUMBER	10

#define MAX_BB_RADIUS	30
#define MIN_BB_RADIUS	5
#define MAX_BB_STEP		1
#define MIN_BB_STEP		5

typedef struct
{
	VMINT x, y;
	VMINT r;
	VMINT step;
} Bubble;

Bubble listBB[BUBBLE_NUMBER];

void pushMainMenu()
{
	StateFuncs* curState;
	if (!isEmptyStateStack())
	{
		curState = getCurrentState();
		curState->leave();
	}
	
	g_TopStateStack++;
	curState = getCurrentState();
	curState->update = updateMainMenu;
	curState->leave = leaveMainMenu;
	curState->destroy = destroyMainMenu;
	curState->resume = resumeMainMenu;

	enterMainMenu();
}

void enterMainMenu()
{
	aLoad = (VMINT*) malloc(sizeof(VMINT) * BOARD_COL * BOARD_ROW);
	
	initMainMenu();

	mainMenuCurHL = 0;

	startEffect(mainMenuItemNumber, mainMenuItemEffect);
	initBubble();
}

void initMainMenu()
{
	VMINT isContinue;
	isContinue = loadBoard(aLoad, &scoreLoad, &typeLoad);

	if (isContinue)
	{
		mainMenuItemNumber = 6;
		mainMenuItemTextList = (VMCHAR*) malloc(sizeof(VMCHAR*) * mainMenuItemNumber);
		mainMenuItemTextList[0] = TXT_CONTINUE;
		mainMenuItemTextList[1] = TXT_NEW_GAME;
		mainMenuItemTextList[2] = TXT_OPTION;
		mainMenuItemTextList[3] = TXT_HIGH_SCORE;
		mainMenuItemTextList[4] = TXT_HELP;
		mainMenuItemTextList[5] = TXT_EXIT;
	}
	else
	{
		mainMenuItemNumber = 5;
		mainMenuItemTextList = (VMCHAR*) malloc(sizeof(VMCHAR*) * mainMenuItemNumber);
		mainMenuItemTextList[0] = TXT_NEW_GAME;
		mainMenuItemTextList[1] = TXT_OPTION;
		mainMenuItemTextList[2] = TXT_HIGH_SCORE;
		mainMenuItemTextList[3] = TXT_HELP;
		mainMenuItemTextList[4] = TXT_EXIT;
	}
}

void leaveMainMenu()
{
}

void destroyMainMenu()
{
	free(mainMenuItemTextList);
	free(aLoad);
}

void renderMainMenu()
{
	renderMenu(mainMenuItemNumber, mainMenuItemTextList, mainMenuItemEffect, mainMenuCurHL);
}

void updateMainMenu()
{
	VMINT i, j, k;
	updateEffect(mainMenuItemNumber, mainMenuItemEffect);
	updateHL(mainMenuItemNumber, &mainMenuCurHL);

	if( isKeyDown( ACTION_KEY_OK ) )
	{
		i = mainMenuCurHL;
		if (mainMenuItemTextList[i] == TXT_CONTINUE)
		{
			isLoadOldGame = 1;
			pushRun();
			return;
		}
		else if (mainMenuItemTextList[i] == TXT_NEW_GAME)
		{
			pushNewGameMenu();
			return;
		}
		else if (mainMenuItemTextList[i] == TXT_OPTION)
		{
			pushOptionMenu();
			return;
		}
		else if (mainMenuItemTextList[i] == TXT_HIGH_SCORE)
		{
			pushHighScoreMenu(TYPE_LINE);
			return;
		}
		else if (mainMenuItemTextList[i] == TXT_HELP)
		{
			pushHelpMenu();
			return;
		}
		else if (mainMenuItemTextList[i] == TXT_EXIT)
		{
			endGame();
			exitGame();
			return;
		}
	}

	renderMainMenu();
}

void resumeMainMenu()
{
	initMainMenu();
	startEffect(mainMenuItemNumber, mainMenuItemEffect);
}

//==================================================================================

void startEffect(VMINT itemNumber, VMINT* itemEffect)
{
	VMINT i;
	for (i = 0; i < itemNumber; i++)
	{
		itemEffect[i] = 0;
	}
}

extern VMINT** optionMenuItemTextList;
extern VMINT** newGameMenuItemTextList;

void renderMenu(VMINT itemNumber, VMCHAR** itemTextList, VMINT* itemEffect, VMINT curHL)
{
	VMINT totalHeight = MENU_ITEM_HEIGHT * itemNumber;
	VMINT y = ((SCREEN_HEIGHT - totalHeight) >> 1) + (MENU_ITEM_HEIGHT >> 1);
	VMINT ycheat;
	VMINT i;
	vm_graphic_fill_rect(g_ScreenBuffer, 0, 0, SCREEN_WIDTH, SCREEN_HEIGHT,
			MENU_BGR_COLOR, MENU_BGR_COLOR);
	
	renderBubble();
	for (i = 0; i < itemNumber; i++)
	{
		if (curHL == i)
		{
			ycheat = 2 + y + itemEffect[i] - (MENU_ITEM_HEIGHT >> 1);

			if (optionMenuItemTextList)
			{
				if (optionMenuItemTextList == itemTextList)
				{
					if (i == 0) ycheat -= 1;
					else ycheat -= 2;
				}
			}
			if (newGameMenuItemTextList)
			{
				if (newGameMenuItemTextList == itemTextList)
				{
					if (i == 1) ycheat -= 1;
				}
			}

			vm_graphic_fill_rect(g_ScreenBuffer, 0, ycheat,
					SCREEN_WIDTH, MENU_ITEM_HEIGHT, MENU_HL_COLOR, MENU_HL_COLOR);
		}
		fontBMDrawString(FONT_LARGE, itemTextList[i],
				SCREEN_WIDTH >> 1, y + itemEffect[i],
				HCENTER | VCENTER
		);
	}
}

void updateEffect(VMINT itemNumber, VMINT* itemEffect)
{
	VMINT i, end, dist;
	
	for (i = 0; i < itemNumber; i++)
	{
		end = i * MENU_ITEM_HEIGHT;
		dist = end - itemEffect[i];
		if (dist > 0)
		{
			itemEffect[i] += (dist >> 2) + 1;
			if (itemEffect[i] > end)
			{
				itemEffect[i] = end;
			}
		}
	}
	updateBubble();
}

void updateHL(VMINT itemNumber, VMINT* curHL)
{
	if( isKeyDown( ACTION_KEY_DOWN ) || isKeyRepeated(ACTION_KEY_DOWN) )
	{
		*curHL = (*curHL) + 1;
		if (*curHL == itemNumber) *curHL = 0;
	}
	if( isKeyDown( ACTION_KEY_UP ) || isKeyRepeated( ACTION_KEY_UP ) )
	{
		*curHL = (*curHL) - 1;
		if (*curHL < 0) *curHL = itemNumber - 1;
	}
}

void renderBackButton()
{
	fontBMDrawString(FONT_MEDIUM, TXT_BACK, 6, 232, LEFT | BOTTOM);
}

void renderBubble()
{
	VMINT i;
	for (i = 0; i < BUBBLE_NUMBER; i++)
	{
		vm_graphic_fill_ellipse (
				g_ScreenBuffer,
				listBB[i].x - listBB[i].r, listBB[i].y - listBB[i].r,
				listBB[i].r << 1, listBB[i].r << 1,
				BUBBLE_COLOR
		);
	}
}

void initBubble()
{
	VMINT i;
	srand(time(NULL));
	
	for (i = 0; i < BUBBLE_NUMBER; i++)
	{
		listBB[i].r = myrandomLR(MIN_BB_RADIUS, MAX_BB_RADIUS);
		listBB[i].x = myrandomLR(listBB[i].r, SCREEN_WIDTH - listBB[i].r);
		listBB[i].y = SCREEN_HEIGHT + listBB[i].r;
		listBB[i].step = myrandomLR(MIN_BB_STEP, MAX_BB_STEP);
	}
}

void updateBubble()
{
	VMINT i;
	for (i = 0; i < BUBBLE_NUMBER; i++)
	{
		listBB[i].y -= listBB[i].step;
		if (listBB[i].y < -listBB[i].r)
		{
			listBB[i].y = SCREEN_HEIGHT + listBB[i].r;
		}
	}
}

//==================================================================================
