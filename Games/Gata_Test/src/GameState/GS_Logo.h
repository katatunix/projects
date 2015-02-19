#ifndef _GS_LOGO_H_
#define _GS_LOGO_H_

#include <gata/game/GameState.h>

#include <gata/gui/Widget.h>

class GS_Logo : public gata::game::GameState, public gata::gui::WidgetListener
{
public:
	GS_Logo();
	virtual ~GS_Logo();

	void create();
	void destroy();

	bool update();
	void render();

	void pause();
	void resume();

	bool isKindOf(int kind);

	// From WidgetListener
	void actionPerformed(gata::gui::Widget* pSenderWidget);

private:
	gata::gui::Widget* m_masterWidget;
};

#endif
