#ifndef _INVISIBLE_KILLERH_
#define _INVISIBLE_KILLERH_

#include "Entity.h"

class InvisibleKiller : public Entity
{
public:
	InvisibleKiller();
	virtual ~InvisibleKiller();

	void render(gata::graphic::Graphic* g);
	void update();

	void solveCollision();
	bool canCollideWith(const GameObject* other) const;

	void startLife();

	void setMaxDY(int v) { m_maxDY = v; }

private:
	int m_maxDY;
	int m_risingStartY;
};

#endif
