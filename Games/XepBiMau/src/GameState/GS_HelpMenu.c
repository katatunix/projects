#include <vmsys.h>

#include "GS_HelpMenu.h"
#include "..\State.h"
#include "..\Game.h"
#include "..\Utils\Utils.h"
#include "..\Utils\FontBM.h"

extern VMINT g_TopStateStack;

extern VMCHAR TXT_HELP_TITLE[];
extern VMCHAR TXT_HELP_LINE[];
extern VMCHAR TXT_HELP_SQUARE[];
extern VMCHAR TXT_HELP_BLOCK[];

void pushHelpMenu()
{
	StateFuncs* curState;
	if (!isEmptyStateStack())
	{
		curState = getCurrentState();
		curState->leave();
	}
	
	g_TopStateStack++;
	curState = getCurrentState();
	curState->update = updateHelpMenu;
	curState->leave = leaveHelpMenu;
	curState->destroy = destroyHelpMenu;
	curState->resume = resumeHelpMenu;

	enterHelpMenu();
}

void enterHelpMenu()
{
}

void leaveHelpMenu()
{
}

void destroyHelpMenu()
{
}

void renderHelpMenu()
{
	VMINT x, y;

	vm_graphic_fill_rect(g_ScreenBuffer, 0, 0, SCREEN_WIDTH, SCREEN_HEIGHT,
			MENU_BGR_COLOR, MENU_BGR_COLOR);
	
	x = 10;
	y = 10;
	fontBMDrawWrapText(FONT_MEDIUM, TXT_HELP_TITLE, x, y, 300, 20);

	y = 50;
	fontBMDrawWrapText(FONT_MEDIUM, TXT_HELP_LINE, x, y, 300, 20);

	y = 110;
	fontBMDrawWrapText(FONT_MEDIUM, TXT_HELP_SQUARE, x, y, 300, 20);

	y = 150;
	fontBMDrawWrapText(FONT_MEDIUM, TXT_HELP_BLOCK, x, y, 300, 20);
	
	renderBackButton();
}

void updateHelpMenu()
{
	if( isKeyDown( ACTION_KEY_SOFTLEFT ) || isKeyDown( ACTION_KEY_CLEAR ) )
	{
		popState();
		return;
	}

	renderHelpMenu();
}

void resumeHelpMenu()
{
}
