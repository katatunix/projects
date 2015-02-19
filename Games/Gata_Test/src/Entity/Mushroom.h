#ifndef _MUSHROOM_H_
#define _MUSHROOM_H_

#include "Entity.h"

#include "../SpriteManager.h"

class Mushroom : public Entity
{
public:
	enum Color
	{
		RED,
		GREEN
	};

	enum State
	{
		RISING,
		RUNNING,
		THROWING
	};

	Mushroom();
	virtual ~Mushroom();

	void render(gata::graphic::Graphic* g);
	void update();
	void solveCollision();
	bool canCollideWith(const GameObject* other) const;

	void startLife();

	//
	void setColor(Color color);

private:
	bool m_originalFacingRight;
	bool m_isFacingRight;

	Color m_color;
	State m_state;

	int m_risingStartY;

	//
	Sprite* m_pSprite;
};

#endif
