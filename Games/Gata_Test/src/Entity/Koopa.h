#ifndef _KOOPA_H_
#define _KOOPA_H_

#include "Entity.h"

#include "../SpriteManager.h"

class Koopa : public Entity
{
public:
	enum Color
	{
		RED,
		GREEN,
		BLUE
	};

	enum State
	{
		WING,
		HEAD,
		NON_HEAD,
		THROWING
	};

	Koopa();
	virtual ~Koopa();

	void render(gata::graphic::Graphic* g);
	void update();
	void solveCollision();
	bool canCollideWith(const GameObject* other) const;

	void startLife();

	//
	bool originalFacingRight() { return m_originalFacingRight; }
	void setOrinigalFacingRight(bool b) { m_originalFacingRight = b; }

	void setOriginalHasWing(bool b) { m_originalHasWing = b; }

	//
	void setColor(Color color);
	inline bool isLiving() const { return m_state != THROWING; }

	bool isGliding() { return m_state == NON_HEAD && m_vx != 0; }
	bool isIdle() { return m_state == NON_HEAD && m_vx == 0; }

private:
	void unmapAllMAnims();

	bool m_originalFacingRight;
	bool m_originalHasWing;

	bool m_isFacingRight;

	Color m_color;
	State m_state;
	int m_nonHeadFrameCount;

	//
	Sprite* m_pSprite;
	
	int m_manimWalkingLeft, m_manimWalkingRight;
	int m_manimFlyingLeft, m_manimFlyingRight;
	int m_manimReborn;

	int m_frameNonHead;
	int m_frameThrowing;
};

#endif
