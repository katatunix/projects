#include <vmsys.h>

#include "GS_HighScoreMenu.h"
#include "..\State.h"
#include "..\Game.h"
#include "..\Utils\Utils.h"
#include "..\Utils\FontBM.h"

extern VMINT g_TopStateStack;

extern VMCHAR TXT_NEXT[];
extern VMCHAR TXT_BACK[];

extern VMCHAR TXT_LINE[];
extern VMCHAR TXT_SQUARE[];
extern VMCHAR TXT_BLOCK[];

GameType curShowType;

ScoreRecList hsList[TYPE_COUNT];

#define HS_X		10
#define HS_Y		30
#define HS_LINE_H	19

#define HS_INDEX_W	20
#define HS_NAME_W	200
#define HS_SCORE_W	60

void pushHighScoreMenu(GameType type)
{
	StateFuncs* curState;
	if (!isEmptyStateStack())
	{
		curState = getCurrentState();
		curState->leave();
	}
	
	g_TopStateStack++;
	curState = getCurrentState();
	curState->update = updateHighScoreMenu;
	curState->leave = leaveHighScoreMenu;
	curState->destroy = destroyHighScoreMenu;
	curState->resume = resumeHighScoreMenu;

	curShowType = type;
	enterHighScoreMenu();
}

void enterHighScoreMenu()
{
	hsList[TYPE_LINE] = loadScore(TYPE_LINE);
	hsList[TYPE_SQUARE] = loadScore(TYPE_SQUARE);
	hsList[TYPE_BLOCK] = loadScore(TYPE_BLOCK);
}

void leaveHighScoreMenu()
{
}

void destroyHighScoreMenu()
{
}

void renderHighScoreMenu()
{
	VMCHAR* title;
	VMINT i, y;

	vm_graphic_fill_rect(g_ScreenBuffer, 0, 0, SCREEN_WIDTH, SCREEN_HEIGHT,
			MENU_BGR_COLOR, MENU_BGR_COLOR);
	fontBMDrawString(FONT_MEDIUM, TXT_NEXT, 315, 232, RIGHT | BOTTOM);
	renderBackButton();

	switch (curShowType)
	{
	case TYPE_LINE:
		title = TXT_LINE;
		break;
	case TYPE_SQUARE:
		title = TXT_SQUARE;
		break;
	case TYPE_BLOCK:
		title = TXT_BLOCK;
		break;
	default:
		return;
	}

	fontBMDrawString(FONT_LARGE, title, SCREEN_WIDTH >> 1, 5, HCENTER | TOP);

	y = HS_Y;
	
	for (i = 0; i < MAX_SCORE_RECORD; i++)
	{
		fontBMDrawNumber(FONT_MEDIUM, (i + 1), HS_X + HS_INDEX_W, y, RIGHT | TOP, 1);

		if (i < hsList[curShowType].number)
		{
			fontBMDrawString(FONT_MEDIUM, hsList[curShowType].rec[i].pName,
					HS_X + HS_INDEX_W + 10, y, LEFT | TOP);
			fontBMDrawNumber(FONT_MEDIUM, hsList[curShowType].rec[i].score,
					HS_X + HS_INDEX_W + 10 + HS_NAME_W + HS_SCORE_W, y, RIGHT | TOP, 0);
		}
		y += HS_LINE_H;
	}
}

void updateHighScoreMenu()
{
	if( isKeyDown( ACTION_KEY_SOFTLEFT ) || isKeyDown( ACTION_KEY_CLEAR ) )
	{
		popState();
		return;
	}

	if( isKeyDown( ACTION_KEY_SOFTRIGHT ) || isKeyDown( ACTION_KEY_RIGHT )
			|| isKeyDown( ACTION_KEY_OK ) )
	{
		curShowType++;
		if (curShowType == TYPE_COUNT)
		{
			curShowType = TYPE_LINE;
		}
	}

	if( isKeyDown( ACTION_KEY_LEFT ) )
	{
		if (curShowType == TYPE_LINE)
			curShowType = TYPE_COUNT - 1;
		else
			curShowType--;
	}

	renderHighScoreMenu();
}

void resumeHighScoreMenu()
{
}
