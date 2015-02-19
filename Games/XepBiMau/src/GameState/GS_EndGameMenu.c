#include <vmsys.h>

#include "GS_EndGameMenu.h"
#include "..\State.h"
#include "..\Game.h"
#include "..\Utils\Utils.h"
#include "..\Utils\FontBM.h"

extern VMINT g_TopStateStack;
extern VMCHAR TXT_END_GAME_SCORE[];
extern VMCHAR TXT_END_GAME_HS[];
extern VMCHAR TXT_END_GAME_TRY[];

extern VMINT score;
extern GameType curGameType;

VMINT scoreEndGame;
GameType typeEndGame;

void pushEndGameMenu()
{
	StateFuncs* curState;
	if (!isEmptyStateStack())
	{
		curState = getCurrentState();
		curState->leave();
	}
	
	g_TopStateStack++;
	curState = getCurrentState();
	curState->update = updateEndGameMenu;
	curState->leave = leaveEndGameMenu;
	curState->destroy = destroyEndGameMenu;
	curState->resume = resumeEndGameMenu;

	enterEndGameMenu();
}

void enterEndGameMenu()
{
	scoreEndGame = score;
	typeEndGame = curGameType;
}

void leaveEndGameMenu()
{
}

void destroyEndGameMenu()
{
}

void renderEndGameMenu()
{
	vm_graphic_fill_rect(g_ScreenBuffer, 0, 0, SCREEN_WIDTH, SCREEN_HEIGHT,
			MENU_BGR_COLOR, MENU_BGR_COLOR);
	fontBMDrawString(FONT_MEDIUM, TXT_END_GAME_SCORE, 20, 20, LEFT | TOP);
	fontDrawNumber(scoreEndGame, 200, 20, LEFT | TOP);

	fontBMDrawString(FONT_MEDIUM, TXT_END_GAME_HS, 20, 50, LEFT | TOP);
	fontBMDrawString(FONT_MEDIUM, TXT_END_GAME_TRY, 20, 80, LEFT | TOP);
	
	renderBackButton();
}

void updateEndGameMenu()
{
	if( isKeyDown( ACTION_KEY_SOFTLEFT ) || isKeyDown( ACTION_KEY_CLEAR ) )
	{
		popState();
		return;
	}

	renderEndGameMenu();
}

void resumeEndGameMenu()
{
}
