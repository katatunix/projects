#include <vmsys.h>

#include "Game.h"
#include "State.h"
#include "Utils\Utils.h"
#include "Utils\FontBM.h"

#include "GameState\GS_Logo.h"

#define BOARD_SAVE_FILE				".\\board.sav"
#define SCORE_LINE_SAVE_FILE		".\\score_line.sav"
#define SCORE_SQUARE_SAVE_FILE		".\\score_square.sav"
#define SCORE_BLOCK_SAVE_FILE		".\\score_block.sav"

extern VMINT g_TopStateStack;

// From GS_OptionMenu
extern VMINT isShowNext;
extern VMINT isSoundOn;

Image ballListImg;

void initGame()
{
	resetKey();
	fontBMInit();
	initSound();

	loadImage(&ballListImg, BALL_IMAGE_FILE);
	isShowNext = 1;
	isSoundOn = 0;

	g_TopStateStack = -1;
	// First state
	pushLogo();
}

void endGame()
{
	unloadImage(&ballListImg);
	freeSound();
	fontBMFree();

	clearStateStack();
}

void updateGame()
{
	getCurrentState()->update();
}

void onGameKey( VMINT event, VMINT keycode )
{
	if( event == VM_KEY_EVENT_DOWN )
	{
		onKeyDown( keycode );
	}
	else if( event == VM_KEY_EVENT_UP )
	{
		onKeyUp( keycode );
	}
	else if (event == VM_KEY_EVENT_LONG_PRESS)
	{
		onKeyRepeat( keycode );
	}
}

void exitGame()
{
	vm_exit_app();
}

void saveBoard(VMINT** a, VMINT score, GameType type)
{
	VMFILE f;
	VMWCHAR nameUCS2[MAX_FILE_NAME_LEN];
	VMUINT t;
	VMINT i;

	vm_ascii_to_ucs2(nameUCS2, MAX_FILE_NAME_LEN, BOARD_SAVE_FILE);
	f = vm_file_open(nameUCS2, MODE_CREATE_ALWAYS_WRITE, TRUE);
	
	if (f < 0)
	{
		return;
	}

	for (i = 0; i < BOARD_COL; i++)
	{
		vm_file_write(f, a[i], sizeof(VMINT) * BOARD_ROW, &t);
	}
	
	vm_file_write(f, &score, sizeof(VMINT), &t);
	vm_file_write(f, &type, sizeof(GameType), &t);

	vm_file_close(f);
}

VMINT loadBoard(VMINT** a, VMINT* score, GameType* type)
{
	VMFILE f;
	VMWCHAR nameUCS2[MAX_FILE_NAME_LEN];
	VMUINT t;

	vm_ascii_to_ucs2(nameUCS2, MAX_FILE_NAME_LEN, BOARD_SAVE_FILE);
	f = vm_file_open(nameUCS2, MODE_READ, TRUE);
	
	if (f < 0)
	{
		return 0;
	}

	if (!vm_file_read(f, a, sizeof(VMINT) * BOARD_COL * BOARD_ROW, &t))
	{
		vm_file_close(f);
		return 0;
	}
	if (t != sizeof(VMINT) * BOARD_COL * BOARD_ROW)
	{
		vm_file_close(f);
		return 0;
	}

	if (!vm_file_read(f, score, sizeof(VMINT), &t))
	{
		vm_file_close(f);
		return 0;
	}
	if (t != sizeof(VMINT))
	{
		vm_file_close(f);
		return 0;
	}

	if (!vm_file_read(f, type, sizeof(GameType), &t))
	{
		vm_file_close(f);
		return 0;
	}
	if (t != sizeof(GameType))
	{
		vm_file_close(f);
		return 0;
	}

	vm_file_close(f);
	return 1;
}

void saveScore(ScoreRecord s, GameType type)
{
	VMFILE f;
	VMWCHAR nameUCS2[MAX_FILE_NAME_LEN];
	VMUINT t;
	VMINT i, found;

	ScoreRecList list;
	list = loadScore(type);
	
	found = -1;
	for (i = 0; i < list.number; i++)
	{
		if (s.score > list.rec[i].score)
		{
			found = i;
			break;
		}
	}

	if (found == -1)
	{
		if (list.number == MAX_SCORE_RECORD)
		{
			return;
		}
		else
		{
			found = list.number;
		}
	}

	if (list.number < MAX_SCORE_RECORD)
	{
		list.number++;
	}

	for (i = list.number - 1; i >= found + 1; i--)
	{
		list.rec[i] = list.rec[i - 1];
	}
	list.rec[found] = s;

	switch (type)
	{
	case TYPE_LINE:
		vm_ascii_to_ucs2(nameUCS2, MAX_FILE_NAME_LEN, SCORE_LINE_SAVE_FILE);
		break;
	case TYPE_SQUARE:
		vm_ascii_to_ucs2(nameUCS2, MAX_FILE_NAME_LEN, SCORE_SQUARE_SAVE_FILE);
		break;
	case TYPE_BLOCK:
		vm_ascii_to_ucs2(nameUCS2, MAX_FILE_NAME_LEN, SCORE_BLOCK_SAVE_FILE);
		break;
	default:
		return;
	}

	f = vm_file_open(nameUCS2, MODE_CREATE_ALWAYS_WRITE, TRUE);
	
	if (f < 0)
	{
		return;
	}

	vm_file_write(f, &list.number, sizeof(VMINT), &t);
	
	for (i = 0; i < list.number; i++)
	{
		vm_file_write(f, &list.rec[i], sizeof(ScoreRecord), &t);
	}

	vm_file_close(f);
}

ScoreRecList loadScore(GameType type)
{
	ScoreRecList list;

	VMFILE f;
	VMWCHAR nameUCS2[MAX_FILE_NAME_LEN];
	VMUINT t;
	VMINT i;

	list.number = 0;

	switch (type)
	{
	case TYPE_LINE:
		vm_ascii_to_ucs2(nameUCS2, MAX_FILE_NAME_LEN, SCORE_LINE_SAVE_FILE);
		break;
	case TYPE_SQUARE:
		vm_ascii_to_ucs2(nameUCS2, MAX_FILE_NAME_LEN, SCORE_SQUARE_SAVE_FILE);
		break;
	case TYPE_BLOCK:
		vm_ascii_to_ucs2(nameUCS2, MAX_FILE_NAME_LEN, SCORE_BLOCK_SAVE_FILE);
		break;
	default:
		return list;
	}

	f = vm_file_open(nameUCS2, MODE_READ, TRUE);
	
	if (f < 0)
	{
		return list;
	}

	if (!vm_file_read(f, &list.number, sizeof(VMINT), &t)) // read
	{
		vm_file_close(f);
		list.number = 0;
		return list;
	}
	if (t != sizeof(VMINT))
	{
		vm_file_close(f);
		list.number = 0;
		return list;
	}
	if (list.number <= 0 || list.number > MAX_SCORE_RECORD)
	{
		vm_file_close(f);
		list.number = 0;
		return list;
	}

	for (i = 0; i < list.number; i++)
	{
		if (!vm_file_read(f, &list.rec[i], sizeof(ScoreRecord), &t)) // read
		{
			vm_file_close(f);
			list.number = 0;
			return list;
		}
		if (t != sizeof(ScoreRecord))
		{
			vm_file_close(f);
			list.number = 0;
			return list;
		}
	}

	vm_file_close(f);
	return list;
}

VMINT isCanUpdateHS(VMINT score, GameType type)
{
	ScoreRecList list;
	list = loadScore(type);
	if (list.number < MAX_SCORE_RECORD) return 1;
	return score > list.rec[list.number - 1].score;
}

VMINT loadBestScore(GameType type)
{
	ScoreRecList list;
	list = loadScore(type);
	if (list.number == 0) return 0;
	return list.rec[0].score;
}
