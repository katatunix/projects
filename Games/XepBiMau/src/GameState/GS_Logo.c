#include <vmsys.h>

#include "GS_Logo.h"
#include "..\State.h"
#include "..\Game.h"
#include "..\Utils\Utils.h"

#include "..\Utils\FontBM.h"

extern VMINT g_TopStateStack;

VMCHAR TXT_GAME[];
VMCHAR TXT_GAME_TITLE[];
VMCHAR TXT_COPYRIGHT[];
VMCHAR TXT_MY_NAME[];

Image imgLogo;

VMINT stateList[MAX_COLOR];

#define BALL_FIST_X		((SCREEN_WIDTH - MAX_COLOR * BALL_WIDTH) >> 1)
#define BALL_FIST_Y		150
#define BLINK_FRAME		8
#define DELAY_TIME		10

VMINT curBall;

VMINT isBlink;
VMINT blinkIndex;
VMINT blinkState;
VMINT outCount;

BallState appFrame[] =
{
	BALL_STATE_NONE, BALL_STATE_TINY, BALL_STATE_SMALL,
	BALL_STATE_MEDIUM, BALL_STATE_NORMAL
};

void pushLogo()
{
	StateFuncs* curState;
	if (!isEmptyStateStack())
	{
		curState = getCurrentState();
		curState->leave();
	}
	
	g_TopStateStack++;
	curState = getCurrentState();
	curState->update = updateLogo;
	curState->leave = leaveLogo;
	curState->destroy = destroyLogo;
	curState->resume = resumeLogo;

	enterLogo();
}

void enterLogo()
{
	VMINT i;
	for (i = 0; i < MAX_COLOR; i++)
	{
		stateList[i] = 0;
	}
	curBall = 0;
	isBlink = 0;
}

void leaveLogo()
{
}

void destroyLogo()
{
}

void renderLogo()
{
	VMINT i;

	memset(g_ScreenBuffer, 0, SCREEN_WIDTH * SCREEN_HEIGHT * 2);
	
	fontBMDrawString(FONT_MEDIUM, TXT_GAME, SCREEN_WIDTH >> 1, 90, HCENTER | VCENTER);
	fontBMDrawString(FONT_LARGE, TXT_GAME_TITLE, SCREEN_WIDTH >> 1, SCREEN_HEIGHT >> 1, HCENTER | VCENTER);
	fontBMDrawString(FONT_MEDIUM, TXT_MY_NAME, SCREEN_WIDTH >> 1, SCREEN_HEIGHT - 30, HCENTER | BOTTOM);
	fontBMDrawString(FONT_MEDIUM, TXT_COPYRIGHT, SCREEN_WIDTH >> 1, SCREEN_HEIGHT - 10, HCENTER | BOTTOM);

	for (i = 0; i < MAX_COLOR; i++)
	{
		renderBall_2(i + 1, appFrame[stateList[i]], BALL_FIST_X + i * BALL_WIDTH, BALL_FIST_Y);
	}
}

void updateLogo()
{
	VMINT i;

	if( isKeyDown( ACTION_KEY_OK ) || isKeyDown( ACTION_KEY_RIGHT ))
	{
		pushAskSoundMenu();
		return;
	}

	if (isBlink)
	{
		blinkIndex++;
		if (blinkIndex == BLINK_FRAME)
		{
			blinkIndex = 0;
			blinkState = !blinkState;
			outCount++;
			if (outCount == DELAY_TIME)
			{
				pushAskSoundMenu();
				return;
			}
		}
		
		for (i = 0; i < MAX_COLOR; i++)
		{
			stateList[i] = blinkState ? 4 : 0;
		}
	}
	else
	{
		if (appFrame[stateList[curBall]] == BALL_STATE_NORMAL)
		{
			if (curBall < MAX_COLOR - 1)
			{
				curBall++;
			}
			else
			{
				isBlink = 1;
				blinkIndex = 0;
				blinkState = 0;
				outCount = 0;
			}
		}
		else
		{
			stateList[curBall]++;
		}
	}

	renderLogo();
}

void resumeLogo()
{
}
