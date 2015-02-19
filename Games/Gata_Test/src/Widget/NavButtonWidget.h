#ifndef _NAV_BUTTON_WIDGET_H_
#define _NAV_BUTTON_WIDGET_H_

#include "PlayingButtonWidget.h"

class NavButtonWidget : public PlayingButtonWidget
{
public:

	void render_Normal(gata::graphic::Graphic* g);
	void render_Active(gata::graphic::Graphic* g);

};


#endif
