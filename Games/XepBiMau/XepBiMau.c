#include "vmsys.h"
#include "vmio.h"
#include "vmgraph.h"
#include "vmchset.h"
#include "vmstdlib.h"

/* ---------------------------------------------------------------------------
 * global variables
 * ------------------------------------------------------------------------ */

#define		SUPPORT_BG

VMINT		g_layer_hdl[1];				// layer handle array.

/* ---------------------------------------------------------------------------
 * local variables
 * ------------------------------------------------------------------------ */
/*
 * system events
 */
void handle_sysevt(VMINT message, VMINT param);

/*
 * key events
 */
void handle_keyevt(VMINT event, VMINT keycode);

/*
 * pen events
 */
void handle_penevt(VMINT event, VMINT x, VMINT y);

// main game
VMUSHORT timerId;
void timer_proc(VMINT tid);

extern void initGame();
extern void endGame();
extern void updateGame();
extern void onGameKey(VMINT event, VMINT keycode);

VMUINT8*	g_ScreenBuffer;
VMINT		g_ScreenWidth;
VMINT		g_ScreenHeight;

/**
 * entry
 */
void vm_main(void) 
{
	g_layer_hdl[0] = -1;

	initGame();

	vm_reg_sysevt_callback(handle_sysevt);
	vm_reg_keyboard_callback(handle_keyevt);
}

void handle_sysevt(VMINT message, VMINT param) 
{
	switch (message) 
    {
		case VM_MSG_CREATE:
		case VM_MSG_ACTIVE:
			// create base layer that has same size as the screen
			if( g_layer_hdl[0] == -1 )
			{
				g_ScreenWidth = vm_graphic_get_screen_width();
				g_ScreenHeight = vm_graphic_get_screen_height();
				g_layer_hdl[0] = vm_graphic_create_layer(0, 0, g_ScreenWidth, g_ScreenHeight, -1);
			}
			
			// set clip area
			vm_graphic_set_clip(0, 0, g_ScreenWidth, g_ScreenHeight);

			// start up
			g_ScreenBuffer = vm_graphic_get_layer_buffer(g_layer_hdl[0]);
			timerId = vm_create_timer( 10, timer_proc );
			break;

		case VM_MSG_SCREEN_ROTATE:
			// The application can rotate the screen
			break;
		
		case VM_MSG_INACTIVE:
            if( g_layer_hdl[0] != -1 )
            {				
				vm_delete_timer( timerId );
                vm_graphic_delete_layer(g_layer_hdl[0]);
                g_layer_hdl[0] = -1;
			}
            break;
		
		case VM_MSG_QUIT:
			if( g_layer_hdl[0] != -1 )
			{
				vm_graphic_delete_layer(g_layer_hdl[0]);
				g_layer_hdl[0] = -1;
			}

			vm_delete_timer( timerId );

			endGame();

            vm_exit_app();
			break;
	}
}

void handle_keyevt(VMINT event, VMINT keycode)
{
	onGameKey(event, keycode);
}

void timer_proc(int tid)
{
	updateGame();
	vm_graphic_flush_layer( g_layer_hdl, 1 );
}
