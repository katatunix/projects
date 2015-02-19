#ifndef _PLAYING_BUTTON_WIDGET_H_
#define _PLAYING_BUTTON_WIDGET_H_

#include <gata/gui/Widget.h>

class PlayingButtonWidget : public gata::gui::Widget
{
public:
	PlayingButtonWidget();
	virtual ~PlayingButtonWidget();

	virtual void render_Normal(gata::graphic::Graphic* g) = 0;
	virtual void render_Active(gata::graphic::Graphic* g) = 0;

	void onMouseHover(int x, int y, int id);
	void onMouseDown(int x, int y, int id);
	void onMouseUp(int x, int y, int id);
	void onMouseOut(int id);

	void onRender(gata::graphic::Graphic* g);
	void onUpdate();

	bool isActive();

	static bool m_allowRender;

private:
	bool m_isActive;
};

#endif
