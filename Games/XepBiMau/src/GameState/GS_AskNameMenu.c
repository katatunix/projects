#include <vmsys.h>

#include "GS_AskNameMenu.h"
#include "..\State.h"
#include "..\Game.h"
#include "..\Utils\Utils.h"
#include "..\Utils\FontBM.h"

#define MINUS_CHAR				17
#define MAX_MINUS_FRAME			6

extern VMINT g_TopStateStack;

extern VMCHAR TXT_END_GAME_SCORE[];
extern VMCHAR TXT_ASK_NAME[];

extern VMCHAR TXT_CANCEL[];
extern VMCHAR TXT_SAVE[];

extern VMUINT8 charKey[];

extern VMINT score;
extern GameType curGameType;

VMINT scoreAskName;
GameType typeAskName;

VMCHAR nameAsk[MAX_PLAYER_NAME_LEN];
VMINT nameAskLen;

VMINT isShowMinus;
VMINT minusFrameCount;

void pushAskNameMenu()
{
	StateFuncs* curState;
	if (!isEmptyStateStack())
	{
		curState = getCurrentState();
		curState->leave();
	}
	
	g_TopStateStack++;
	curState = getCurrentState();
	curState->update = updateAskNameMenu;
	curState->leave = leaveAskNameMenu;
	curState->destroy = destroyAskNameMenu;
	curState->resume = resumeAskNameMenu;

	enterAskNameMenu();
}

void enterAskNameMenu()
{
	nameAskLen = 1;
	nameAsk[0] = -1;
	nameAsk[1] = -2;

	isShowMinus = 0;
	minusFrameCount = 0;

	scoreAskName = score;
	typeAskName = curGameType;
}

void leaveAskNameMenu()
{
}

void destroyAskNameMenu()
{
}

void renderAskNameMenu()
{
	vm_graphic_fill_rect(g_ScreenBuffer, 0, 0, SCREEN_WIDTH, SCREEN_HEIGHT,
			MENU_BGR_COLOR, MENU_BGR_COLOR);
	
	fontBMDrawString(FONT_MEDIUM, TXT_END_GAME_SCORE, 20, 20, LEFT | TOP);
	fontDrawNumber(scoreAskName, 200, 20, LEFT | TOP);

	fontBMDrawString(FONT_MEDIUM, TXT_ASK_NAME, 20, 50, LEFT | TOP);

	nameAsk[nameAskLen - 1] = isShowMinus ? MINUS_CHAR : -1;

	fontBMDrawString(FONT_LARGE, nameAsk, SCREEN_WIDTH >> 1, SCREEN_HEIGHT >> 1, HCENTER | VCENTER);
	
	fontBMDrawString(FONT_MEDIUM, TXT_CANCEL, 315, 232, RIGHT | BOTTOM);
	fontBMDrawString(FONT_MEDIUM, TXT_SAVE, 6, 232, LEFT | BOTTOM);
}

void updateAskNameMenu()
{
	VMINT i, t;

	if( isKeyDown( ACTION_KEY_SOFTRIGHT ) )
	{
		popState();
		return;
	}

	minusFrameCount++;
	if (minusFrameCount == MAX_MINUS_FRAME)
	{
		minusFrameCount = 0;
		isShowMinus = !isShowMinus;
	}

	if (nameAskLen == MAX_PLAYER_NAME_LEN)
	{
		memset(charKey, 0, sizeof(VMUINT8) * (VM_KEY_z + 1));
	}
	else
	{
		for (i = VM_KEY_SPACE; i <= VM_KEY_z; i++)
		{
			if (charKey[i])
			{
				if (i == VM_KEY_SPACE)
				{
					t = -1;
				}
				else if (i >= VM_KEY_a)
				{
					t = i - 77;
				}
				else
				{
					t = i - 45;
				}

				nameAskLen++;

				nameAsk[nameAskLen] = -2;
				nameAsk[nameAskLen - 1] = nameAsk[nameAskLen - 2];
				nameAsk[nameAskLen - 2] = t;
				charKey[i] = 0;
			}
		}
	}

	if (isKeyDown(ACTION_KEY_CLEAR))
	{
		if (nameAskLen > 1)
		{
			nameAskLen--;
			nameAsk[nameAskLen - 1] = nameAsk[nameAskLen];
			nameAsk[nameAskLen] = -2;
		}

	}

	if (isKeyDown(ACTION_KEY_SOFTLEFT) || isKeyDown(ACTION_KEY_OK))
	{
		if (nameAskLen > 1)
		{
			ScoreRecord s;
			s.score = scoreAskName;
			memcpy(s.pName, nameAsk, MAX_PLAYER_NAME_LEN * sizeof(VMCHAR));
			s.pName[nameAskLen - 1] = -2;
			saveScore(s, typeAskName);

			popState();
			pushHighScoreMenu(typeAskName);
			return;
		}
	}

	if (isKeyDown(ACTION_KEY_SOFTRIGHT))
	{
		popState();
		return;
	}

	renderAskNameMenu();
}

void resumeAskNameMenu()
{
}
