#include "GS_Run.h"
#include "..\State.h"
#include "..\Game.h"
#include "..\Utils\Utils.h"
#include "..\Utils\Algorithms.h"
#include "..\Utils\FontBM.h"

#define HOME_IMAGE_FILE "home.gif"
#define UNDO_IMAGE_FILE "undo.gif"

#define TXT_SCORE_X				(SCREEN_WIDTH - 3)
#define TXT_SCORE_Y				4
#define TXT_SCORE_ANCHOR		(RIGHT | TOP)

#define TXT_SCOREVALUE_X		(SCREEN_WIDTH - 0)
#define TXT_SCOREVALUE_Y		30
#define TXT_SCOREVALUE_ANCHOR	(RIGHT | TOP)

extern VMCHAR TXT_X[];
extern VMCHAR TXT_D[];
extern VMCHAR TXT_T[];
extern VMCHAR TXT_C[];
extern VMCHAR TXT_N[];
extern VMCHAR TXT_K[];

extern VMCHAR TXT_BEST_SCORE[];
extern VMCHAR TXT_BEST_SCORE2[];

#define GAME_TYPE_X			20
#define GAME_TYPE_Y			10
#define GAME_TYPE_H			30
#define GAME_TYPE_ANCHOR	(HCENTER | TOP)

extern VMINT g_TopStateStack;
extern VMCHAR TXT_SCORE[];

extern Image ballListImg;
extern VMINT isShowNext;

// From GS_MainMenu
extern VMINT* aLoad;
extern VMINT scoreLoad;
extern GameType typeLoad;

extern VMINT isSoundOn;


#define JUMP_LEN			6
#define JUMP_FRAME_LEN		2
BallState jumpEffect[JUMP_LEN] =
{
	BALL_STATE_NORMAL, BALL_STATE_MEDIUM, BALL_STATE_SMALL,
	BALL_STATE_TINY, BALL_STATE_SMALL, BALL_STATE_MEDIUM
};

#define APPEAR_LEN			4
#define APPEAR_FRAME_LEN	2
BallState appearEffect[APPEAR_LEN] =
{
	BALL_STATE_TINY, BALL_STATE_SMALL, BALL_STATE_MEDIUM, BALL_STATE_NORMAL
};

GameType curGameType;

PointList path;
VMINT pathIndex;

PointList eatList;
VMINT blinkIndex;
#define BLINK_FRAME_LEN	9

VMINT** a;
VMINT** b;
VMINT isLoadOldGame;

Point cursor;

VMINT isSelected;
Point selected;
VMINT jumpFrame;
VMINT jumpFrameCount;

VMINT appearFrame;
VMINT appearFrameCount;

VMINT score;
VMINT bestScore;
VMINT backupScore;
VMINT isCanUndo;

Image homeImg;
Image undoImg;

void pushRun()
{
	StateFuncs* curState;
	if (!isEmptyStateStack())
	{
		curState = getCurrentState();
		curState->leave();
	}
	
	g_TopStateStack++;
	curState = getCurrentState();

	enterRun();

	stopMusic();

	curState->update = updateRun;
	curState->leave = leaveRun;
	curState->destroy = destroyRun;
	curState->resume = resumeRun;
}

void enterRun()
{
	VMINT i, j, k;
	a = malloc(sizeof(VMINT*) * BOARD_COL);
	for (i = 0; i < BOARD_COL; i++)
	{
		a[i] = malloc(sizeof(VMINT) * BOARD_ROW);
	}
	b = malloc(sizeof(VMINT*) * BOARD_COL);
	for (i = 0; i < BOARD_COL; i++)
	{
		b[i] = malloc(sizeof(VMINT) * BOARD_ROW);
	}

	if (isLoadOldGame)
	{
		k = 0;
		for (i = 0; i < BOARD_COL; i++)
			for (j = 0; j < BOARD_ROW; j++)
				a[i][j] = aLoad[k++];
		score = scoreLoad;
		curGameType = typeLoad;
	}
	else
	{
		makeRandMatrix(a);
		score = 0;
	}
	bestScore = loadBestScore(curGameType);
	cursor.x = BOARD_COL >> 1;
	cursor.y = BOARD_ROW >> 1;

	loadImage(&homeImg, HOME_IMAGE_FILE);
	loadImage(&undoImg, UNDO_IMAGE_FILE);

	isSelected = 0; // jump
	appearFrame = -1; // appear
	path.len = 0; // move
	eatList.len = 0; // destruct

	isCanUndo = 0;
}

void updateRun()
{
	VMINT i, j;
	PointList list, list1;

	if( isKeyDown( ACTION_KEY_SOFTLEFT ) )
	{
		saveBoard(a, score, curGameType);
		popState();
		if (isSoundOn)
		{
			playMusic();
		}
		return;
	}

	if( isKeyDown( ACTION_KEY_LEFT ) || isKeyRepeated( ACTION_KEY_LEFT ) )
	{
		if (cursor.x == 0) cursor.x = BOARD_COL - 1;
		else cursor.x--;
	}
	if( isKeyDown( ACTION_KEY_RIGHT ) || isKeyRepeated( ACTION_KEY_RIGHT ) )
	{
		if (cursor.x == BOARD_COL - 1) cursor.x = 0;
		else cursor.x++;
	}

	if( isKeyDown( ACTION_KEY_UP ) || isKeyRepeated( ACTION_KEY_UP ) )
	{
		if (cursor.y == 0) cursor.y = BOARD_ROW - 1;
		else cursor.y--;
	}
	if( isKeyDown( ACTION_KEY_DOWN ) || isKeyRepeated( ACTION_KEY_DOWN ) )
	{
		if (cursor.y == BOARD_ROW - 1) cursor.y = 0;
		else cursor.y++;
	}

	if( isKeyDown( ACTION_KEY_SOFTLEFT ) )
	{
		i = i;
	}

	if( isKeyDown( ACTION_KEY_SOFTRIGHT ) || isKeyDown( ACTION_KEY_CLEAR ) )
	{
		if (isCanUndo)
		{
			isSelected = 0; // jump
			appearFrame = -1; // appear
			path.len = 0; // move
			eatList.len = 0; // destruct
			restore();
			isCanUndo = 0;
		}
	}

	if ((eatList.len == 0 && appearFrame == -1 && path.len == 0) &&	isKeyDown(ACTION_KEY_OK))
	{
		if (isSelected)
		{
			if (a[cursor.x][cursor.y] <= 0)
			{
				path = findPath(a, selected.x, selected.y, cursor.x, cursor.y);
				if (path.len > 0)
				{
					pathIndex = 0;
					isSelected = 0;
					backup();
					isCanUndo = 1;
				}
			}
			else if (cursor.x == selected.x && cursor.y == selected.y)
			{
				isSelected = 0;
			}
			else
			{
				jumpFrame = 0;
				jumpFrameCount = 0;
				selected.x = cursor.x;
				selected.y = cursor.y;
			}
		}
		else
		{
			if (a[cursor.x][cursor.y] > 0)
			{
				isSelected = 1;
				jumpFrame = 0;
				jumpFrameCount = 0;
				selected.x = cursor.x;
				selected.y = cursor.y;
			}
		}
	}

	if (isSelected)
	{
		jumpFrameCount++;
		if (jumpFrameCount == JUMP_FRAME_LEN)
		{
			jumpFrameCount = 0;
			jumpFrame++;
			if (jumpFrame == JUMP_LEN) jumpFrame = 0;
		}
	}

	if (path.len > 0)
	{
		pathIndex++;
		if (pathIndex == path.len)
		{
			i = path.point[path.len - 1].x;
			j = path.point[path.len - 1].y;
			a[i][j] = a[path.point[0].x][path.point[0].y];
			a[path.point[0].x][path.point[0].y] = 0;
			path.len = 0;

			eatList = checkBall(a, i, j, curGameType);
			if (eatList.len > 0)
			{
				blinkIndex = 0;
				if (isSoundOn)
					playSfxScore();
			}
			else
			{
				appearFrame = 0;
				if (isSoundOn)
					playSfxMove();
			}
		}
	}

	if (appearFrame > -1)
	{
		appearFrameCount++;
		if (appearFrameCount == APPEAR_FRAME_LEN)
		{
			appearFrameCount = 0;
			appearFrame++;
			if (appearFrame == APPEAR_LEN)
			{
				appearFrame = -1;

				list.len = 0;
				for (i = 0; i < BOARD_COL; i++)
					for (j = 0; j < BOARD_ROW; j++)
						if (a[i][j] < 0)
						{
							a[i][j] = -a[i][j];
							list.point[list.len].x = i;
							list.point[list.len].y = j;
							list.len++;
						}
				if (list.len > 0)
				{
					list1.len = 0;
					for (i = 0; i < list.len; i++)
					{
						list1 = mergeBall(list1,
								checkBall(a, list.point[i].x, list.point[i].y, curGameType));
					}
					if (list1.len > 0)
					{
						eatList = list1;
						blinkIndex = 0;
						if (isSoundOn)
							playSfxScore();
					}
				}

				if (countEmpty(a) < NEXT_BALL_NUM)
				{
					gameOver();
					return;
				}

				addNextColor(a);
			}
		}
	}

	if (eatList.len > 0)
	{
		blinkIndex++;
		if (blinkIndex == BLINK_FRAME_LEN)
		{
			for (i = 0; i < eatList.len; i++)
			{
				a[eatList.point[i].x][eatList.point[i].y] = 0;
			}
			score += calcuScore(eatList.len, curGameType);
			eatList.len = 0;
		}
	}

	renderRun();
}

void leaveRun()
{
}

void destroyRun()
{
	VMINT i;

	for (i = 0; i < BOARD_ROW; i++)
	{
		free(b[i]);
	}
	free(b);
	
	for (i = 0; i < BOARD_ROW; i++)
	{
		free(a[i]);
	}
	free(a);
}

void renderBall(VMINT color, BallState state, VMINT desX, VMINT desY)
{
	desX += BOARD_X;
	desY += BOARD_Y;

	if (state == BALL_STATE_NONE)
	{
		color = 0;
		state = BALL_STATE_NORMAL;
	}

	vm_graphic_blt(
			(VMBYTE*) g_ScreenBuffer,
			desX, desY, ballListImg.buffer,
			BALL_WIDTH * color, BALL_HEIGHT * state,
			BALL_WIDTH, BALL_HEIGHT, 1
	);
}

void renderBall_2(VMINT color, BallState state, VMINT desX, VMINT desY)
{
	if (state == BALL_STATE_NONE)
	{
		color = 0;
		state = BALL_STATE_NORMAL;
	}

	vm_graphic_blt(
			(VMBYTE*) g_ScreenBuffer,
			desX, desY, ballListImg.buffer,
			BALL_WIDTH * color, BALL_HEIGHT * state,
			BALL_WIDTH, BALL_HEIGHT, 1
	);
}

void renderBoard()
{
	VMINT i, j, k, color;
	BallState state;

	// Paint ball list
	for (i = 0; i < BOARD_COL; i++)
	{
		for (j = 0; j < BOARD_ROW; j++)
		{
			if (isSelected)
			{
				if (i == selected.x && j == selected.y)
					continue;
			}
			if (path.len > 0)
			{
				if (i == path.point[0].x && j == path.point[0].y)
				{
					color = 0;
					state = BALL_STATE_NORMAL;
					renderBall(color, state, BALL_WIDTH * i, BALL_HEIGHT * j);
					continue;
				}
			}
			if (appearFrame > -1)
			{
				if (a[i][j] < 0)
				{
					color = -a[i][j];
					state = appearEffect[appearFrame];
					renderBall(color, state, BALL_WIDTH * i, BALL_HEIGHT * j);
					continue;
				}
			}
			if (a[i][j] >= 0)
			{
				state = BALL_STATE_NORMAL;
				color = a[i][j];
			}
			else
			{
				state = BALL_STATE_TINY;
				color = isShowNext ? -a[i][j] : 0;
			}
			renderBall(color, state, BALL_WIDTH * i, BALL_HEIGHT * j);
		}
	}

	// Paint the selected ball
	if (isSelected)
	{
		color = a[selected.x][selected.y];
		state = jumpEffect[jumpFrame];
		renderBall(color, state, BALL_WIDTH * selected.x, BALL_HEIGHT * selected.y);
	}

	// Paint the moving ball
	if (path.len > 0)
	{
		color = a[path.point[0].x][path.point[0].y];
		state = BALL_STATE_NORMAL;
		renderBall(color, state, BALL_WIDTH * path.point[pathIndex].x, BALL_HEIGHT * path.point[pathIndex].y);
	}

	// Paint blink ball
	if (eatList.len > 0)
	{
		for (k = 0; k < eatList.len; k++)
		{
			i = eatList.point[k].x;
			j = eatList.point[k].y;
			color = blinkIndex % 2 == 0 ? a[i][j] : 0;
			state = BALL_STATE_NORMAL;
			renderBall(color, state, BALL_WIDTH * i, BALL_HEIGHT * j);
		}
	}

	// Paint cursor
	vm_graphic_rect(
			(VMBYTE*) g_ScreenBuffer,
			BOARD_X + cursor.x * BALL_WIDTH, BOARD_Y + cursor.y * BALL_HEIGHT,
			BALL_WIDTH, BALL_HEIGHT,
			CURSOR_COLOR
	);
}

void renderOther()
{
	vm_graphic_blt((VMBYTE*) g_ScreenBuffer, 9, 210, homeImg.buffer,
			0, 0, homeImg.width, homeImg.height, 1);
	vm_graphic_blt((VMBYTE*) g_ScreenBuffer, 285, 210, undoImg.buffer,
			0, 0, undoImg.width, undoImg.height, 1);

	fontBMDrawString(FONT_MEDIUM, TXT_SCORE, TXT_SCORE_X, TXT_SCORE_Y, TXT_SCORE_ANCHOR);
	fontDrawNumber(score, TXT_SCOREVALUE_X, TXT_SCOREVALUE_Y, TXT_SCOREVALUE_ANCHOR);

	fontBMDrawString(FONT_MEDIUM, TXT_BEST_SCORE, 298, 74, HCENTER | VCENTER);
	fontBMDrawString(FONT_MEDIUM, TXT_BEST_SCORE2, 298, 88, HCENTER | VCENTER);
	fontDrawNumber(bestScore, TXT_SCOREVALUE_X, 107, TXT_SCOREVALUE_ANCHOR);

	switch (curGameType)
	{
	case TYPE_LINE:
		fontBMDrawString(FONT_LARGE, TXT_X, GAME_TYPE_X, GAME_TYPE_Y, GAME_TYPE_ANCHOR);
		fontBMDrawString(FONT_LARGE, TXT_D, GAME_TYPE_X, GAME_TYPE_Y + GAME_TYPE_H, GAME_TYPE_ANCHOR);
		fontBMDrawString(FONT_LARGE, TXT_T, GAME_TYPE_X, GAME_TYPE_Y + GAME_TYPE_H * 2, GAME_TYPE_ANCHOR);
		break;
	case TYPE_SQUARE:
		fontBMDrawString(FONT_LARGE, TXT_X, GAME_TYPE_X, GAME_TYPE_Y, GAME_TYPE_ANCHOR);
		fontBMDrawString(FONT_LARGE, TXT_C, GAME_TYPE_X, GAME_TYPE_Y + GAME_TYPE_H, GAME_TYPE_ANCHOR);
		fontBMDrawString(FONT_LARGE, TXT_N, GAME_TYPE_X, GAME_TYPE_Y + GAME_TYPE_H * 2, GAME_TYPE_ANCHOR);
		break;
	case TYPE_BLOCK:
		fontBMDrawString(FONT_LARGE, TXT_X, GAME_TYPE_X, GAME_TYPE_Y, GAME_TYPE_ANCHOR);
		fontBMDrawString(FONT_LARGE, TXT_K, GAME_TYPE_X, GAME_TYPE_Y + GAME_TYPE_H, GAME_TYPE_ANCHOR);
		break;
	}
}

void renderRun()
{
	memset(g_ScreenBuffer, 0, SCREEN_WIDTH * SCREEN_HEIGHT * 2);

	renderBoard();
	renderOther();
}

void gameOver()
{
	if (isCanUpdateHS(score, curGameType))
	{
		pushAskNameMenu();
	}
	else
	{
		pushEndGameMenu();
	}
}

void backup()
{
	VMINT i, j;
	for (i = 0; i < BOARD_COL; i++)
		for (j = 0; j < BOARD_ROW; j++)
			b[i][j] = a[i][j];
	backupScore = score;
}

void restore()
{
	VMINT i, j;
	for (i = 0; i < BOARD_COL; i++)
		for (j = 0; j < BOARD_ROW; j++)
			a[i][j] = b[i][j];
	score = backupScore;
}

void resumeRun()
{
	makeRandMatrix(a);
	score = 0;
	cursor.x = BOARD_COL >> 1;
	cursor.y = BOARD_ROW >> 1;

	isSelected = 0; // jump
	appearFrame = -1; // appear
	path.len = 0; // move
	eatList.len = 0; // destruct

	isCanUndo = 0;

	bestScore = loadBestScore(curGameType);
}
