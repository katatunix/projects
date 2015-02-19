#ifndef _CGAME_H_
#define _CGAME_H_

#include <gata/game/Game.h>
#include <gata/core/Singleton.h>

#define GAME_CLEAR_COLOR	0x6B8CFFFF

#define STATE_LOGO			0
#define STATE_IN_GAME		1

#define KEY_LEFT			0x25
#define KEY_UP				0x26
#define KEY_RIGHT			0x27
#define KEY_DOWN			0x28
#define KEY_A				'Z'
#define KEY_B				'X'
#define KEY_EXIT			0x1B

class CGame : public gata::game::Game, public gata::core::Singleton<CGame>
{
public:
	CGame();
	virtual ~CGame();

	void init();

	void renderFps();
};

#define g_pGame			CGame::getInstance()
#define getGame()		CGame::getInstance()
#define g_pGraphic		CGame::getInstance()->getGraphic()
#define getGraphic()	CGame::getInstance()->getGraphic()

#endif
