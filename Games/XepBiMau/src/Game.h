#ifndef _GAME_H_
#define _GAME_H_

#include <stdlib.h>
#include <stdio.h>
#include "vmio.h"
#include "vmsys.h"
#include "vmres.h"
#include "vmgraph.h"

#include "Define.h"

#include "Key.h"

typedef struct
{
	VMINT		score;
	VMCHAR		pName[MAX_PLAYER_NAME_LEN];
} ScoreRecord;

typedef struct
{
	VMINT number;
	ScoreRecord rec[MAX_SCORE_RECORD];
} ScoreRecList;

extern VMUINT16*	g_ScreenBuffer;
extern VMINT		g_ScreenWidth;
extern VMINT		g_ScreenHeight;

void initGame();
void updateGame();
void endGame();
void exitGame();

void onGameKey(VMINT event, VMINT keycode);

void saveBoard(VMINT** a, VMINT score, GameType type);
VMINT loadBoard(VMINT** a, VMINT* score, GameType* type);

void saveScore(ScoreRecord s, GameType type);
ScoreRecList loadScore(GameType type);
VMINT isCanUpdateHS(VMINT score);
VMINT loadBestScore(GameType type);

#endif
