#ifndef _NORMAL_BRICK_H_
#define _NORMAL_BRICK_H_

#include "TiledObject.h"
#include "../SpriteManager.h"

class NormalBrick : public TiledObject
{
public:
	NormalBrick(int layer, int col, int row, World* pWorld);

	void render(gata::graphic::Graphic* g);
	void update();

	bool canCollideWith(const GameObject* other) const;
	void solveCollision();

private:
	gata::sprite::Sprite* m_pSprite;
	
};

#endif
