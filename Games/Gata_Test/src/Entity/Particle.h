#ifndef _PARTICLE_H_
#define _PARTICLE_H_

#include "Entity.h"

#include "../SpriteManager.h"

class Particle : public Entity
{
public:
	enum Color
	{
		RED,
		BLUE
	};

	Particle();
	virtual ~Particle();

	void render(gata::graphic::Graphic* g);
	void update();
	void solveCollision();
	bool canCollideWith(const GameObject* other) const;

	void startLife();

	//
	void setColor(Color color);

private:
	Color m_color;

	float m_ax1, m_ay1, m_vx1, m_vy1, m_x1, m_y1;
	float m_ax2, m_ay2, m_vx2, m_vy2, m_x2, m_y2;

	//
	Sprite* m_pSprite;
};

#endif
