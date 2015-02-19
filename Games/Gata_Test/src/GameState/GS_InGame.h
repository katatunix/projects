#ifndef _GS_IN_GAME_H_
#define _GS_IN_GAME_H_

#include <gata/game/GameState.h>

#include "../World.h"

#include "../Widget/NavButtonWidget.h"

class GS_InGame : public gata::game::GameState
{
public:
	GS_InGame();
	virtual ~GS_InGame();

	void create();
	void destroy();

	bool update();
	void render();

	void pause();
	void resume();

	bool isKindOf(int kind);

private:
	void setupWidgetsPosition();

	World m_world;
	float m_scale;

	//
	gata::gui::Widget* m_pMasterWidget;

	NavButtonWidget* m_pJumpWidget;
	NavButtonWidget* m_pFastWidget;

	NavButtonWidget* m_pLeftWidget;
	NavButtonWidget* m_pRightWidget;
};

#endif
