#ifndef _BACKGROUND_H_
#define _BACKGROUND_H_

#include "TiledObject.h"
#include "../SpriteManager.h"

class Background : public TiledObject
{
public:
	Background(int layer, int col, int row, World* pWorld, int data);

	void render(gata::graphic::Graphic* g);
	void update();

	bool canCollideWith(const GameObject* other) const;
	void solveCollision();

	bool isOpaque() { return m_isOpaque; }

private:
	gata::sprite::Sprite* m_pSrite;
	int m_data;

	bool m_isOpaque;
	
};

#endif
