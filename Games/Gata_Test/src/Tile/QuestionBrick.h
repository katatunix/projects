#ifndef _QUESTION_BRICK_H_
#define _QUESTION_BRICK_H_

#include "TiledObject.h"
#include "../SpriteManager.h"

class QuestionBrick : public TiledObject
{
public:
	enum GiftType
	{
		COIN,
		POWER_UP, // mushroom or flower
		STAR,
		LIFE_UP,
	};

	QuestionBrick(int layer, int col, int row, World* pWorld);
	virtual ~QuestionBrick();

	void render(gata::graphic::Graphic* g);
	void update();

	bool canCollideWith(const GameObject* other) const;
	void solveCollision();

	void setGif(GiftType g) { m_gift = g; }
	GiftType getGif() { return m_gift; }

private:
	gata::sprite::Sprite* m_pTilesSprite;
	gata::sprite::Sprite* m_pOtherSprite;
	int m_manimBlink;
	int m_manimCoinSpinner;

	GiftType m_gift;
	bool m_isOpened;

	int m_dyCoinSpinner;
};

#endif
