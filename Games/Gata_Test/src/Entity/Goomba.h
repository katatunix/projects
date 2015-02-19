#ifndef _GOOMBA_H_
#define _GOOMBA_H_

#include "Entity.h"

#include "../SpriteManager.h"

class Goomba : public Entity
{
public:
	enum Color
	{
		RED,
		BLUE,
		GRAY
	};

	enum State
	{
		LIVING,
		DYING,
		THROWING
	};

	Goomba();
	virtual ~Goomba();

	void render(gata::graphic::Graphic* g);
	void update();
	void solveCollision();
	bool canCollideWith(const GameObject* other) const;

	void startLife();

	//
	bool originalFacingRight() { return m_originalFacingRight; }
	void setOrinigalFacingRight(bool b) { m_originalFacingRight = b; }

	//
	void setColor(Color color);
	inline bool isLiving() const { return m_state == LIVING; }

private:
	bool m_originalFacingRight;
	bool m_isFacingRight;

	Color m_color;
	State m_state;

	//
	Sprite* m_pSprite;
	int m_manim;
	int m_frameDying;
	int m_frameThrowing;

	int m_dyingFrameCount;
};

#endif
