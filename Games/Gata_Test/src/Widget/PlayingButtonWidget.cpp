#include "PlayingButtonWidget.h"

bool PlayingButtonWidget::m_allowRender = false;

PlayingButtonWidget::PlayingButtonWidget() : m_isActive(false)
{
	
}

PlayingButtonWidget::~PlayingButtonWidget()
{
}

void PlayingButtonWidget::onMouseHover(int x, int y, int id)
{
	Widget::onMouseHover(x, y, id);
}

void PlayingButtonWidget::onMouseDown(int x, int y, int id)
{
	Widget::onMouseDown(x, y, id);
	m_isActive = true;
}

void PlayingButtonWidget::onMouseUp(int x, int y, int id)
{
	Widget::onMouseUp(x, y, id);
	m_isActive = false;
}

void PlayingButtonWidget::onMouseOut(int id)
{
	Widget::onMouseOut(id);
	m_isActive = false;
}

void PlayingButtonWidget::onRender(gata::graphic::Graphic* g)
{
	if (m_isActive)
	{
		render_Active(g);
	}
	else
	{
		render_Normal(g);
	}
}

void PlayingButtonWidget::onUpdate()
{
}

bool PlayingButtonWidget::isActive()
{
	return m_isActive;
}
