#include "NavButtonWidget.h"
#include <gata/graphic/Graphic.h>

void NavButtonWidget::render_Normal(gata::graphic::Graphic* g)
{
	if (m_allowRender)
	{
		g->setColor(0xFF00FF7F);
		g->fillRect(m_xAbsolute, m_yAbsolute, m_width, m_height);
	}
}

void NavButtonWidget::render_Active(gata::graphic::Graphic* g)
{
	if (m_allowRender)
	{
		g->setColor(0);
		g->fillRect(m_xAbsolute, m_yAbsolute, m_width, m_height);
	}
}
