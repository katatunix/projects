#ifndef _DEFINE_H_
#define _DEFINE_H_

#define MAX_FILE_NAME_LEN		128

#define SCREEN_WIDTH			320
#define SCREEN_HEIGHT			240

#define BOARD_COL				9
#define BOARD_ROW				9

#define BALL_WIDTH				26
#define BALL_HEIGHT				26

#define BOARD_WIDTH				(BOARD_COL * BALL_WIDTH)
#define BOARD_HEIGHT			(BOARD_ROW * BALL_HEIGHT)

#define BOARD_X					((SCREEN_WIDTH - BOARD_WIDTH) >> 1)
#define BOARD_Y					((SCREEN_HEIGHT - BOARD_HEIGHT) >> 1)

#define NEXT_BALL_NUM			3
#define INIT_BALL_NUM			5
#define MAX_COLOR				7
#define CURSOR_COLOR			VM_COLOR_RED

#define EAT_BALL_LINE_NUM		5
#define EAT_BALL_SQUARE_NUM		4
#define EAT_BALL_BLOCK_NUM		7

#define MENU_BGR_COLOR			VM_COLOR_BLUE
#define MENU_HL_COLOR			VM_COLOR_RED
#define MENU_ITEM_HEIGHT		32
#define MAX_ITEM_CHAR_LEN		100

#define MAX_PLAYER_NAME_LEN		13
#define MAX_SCORE_RECORD		10

#define BALL_IMAGE_FILE			"ball.gif"

typedef enum
{
	BALL_STATE_SMALL	= 0,
	BALL_STATE_MEDIUM	= 1,
	BALL_STATE_NORMAL	= 2,
	BALL_STATE_TINY		= 3,
	BALL_STATE_NONE		= 4,
} BallState;

typedef struct
{
	VMINT x;
	VMINT y;
} Point;

typedef struct
{
	VMINT len;
	Point point[BOARD_COL * BOARD_ROW];
} PointList;

typedef enum
{
	TYPE_LINE		= 0,
	TYPE_SQUARE		= 1,
	TYPE_BLOCK		= 2,
	TYPE_COUNT		= 3,
} GameType;

#endif
