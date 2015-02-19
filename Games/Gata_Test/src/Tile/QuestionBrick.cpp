#include "QuestionBrick.h"

#include "../SpriteManager.h"
#include "../ImageManager.h"

#include"../World.h"

#include "../GameObjectPool.h"

#include "../Entity/Particle.h"
#include "../Entity/InvisibleKiller.h"
#include "../Entity/Mushroom.h"

#define MAX_DY_COIN_SPINNER 72
#define DY_COIN_SPINNER_STEP 6

QuestionBrick::QuestionBrick(int layer, int col, int row, World* pWorld) : TiledObject(layer, col, row, pWorld)
{
	m_kind = KIND_TILE_QUESTION_BRICK;

	m_pTilesSprite = getSprite(SPRITE_tiles_myspr);
	m_pTilesSprite->setImage( getImage(IMAGE_tiles_tga) );

	m_pOtherSprite = getSprite(SPRITE_other_myspr);
	m_pOtherSprite->setImage( getImage(IMAGE_other_tga) );

	m_gift = POWER_UP;//COIN;
	m_isOpened = false;

	m_manimBlink = m_pOtherSprite->mapAnim(ANIM_QUESTION_BRICK_BLINK);
	m_manimCoinSpinner = m_pOtherSprite->mapAnim(ANIM_COIN_SPINNER);
}

QuestionBrick::~QuestionBrick()
{
	m_pOtherSprite->unmapMAnim(m_manimBlink);
	m_pOtherSprite->unmapMAnim(m_manimCoinSpinner);
}

void QuestionBrick::render(gata::graphic::Graphic* g)
{
	assert(m_isLive);
	int xRender = m_px - m_pWorld->currentCameraX();
	int yRender = m_py - m_pWorld->currentCameraY();

	if (m_isOpened)
	{
		if (m_gift == COIN)
		{
			if (m_dyCoinSpinner < MAX_DY_COIN_SPINNER)
			{
				m_pOtherSprite->paintAndUpdateMAnim(g, m_manimCoinSpinner, xRender + m_width / 2, m_py - m_dyCoinSpinner);
			}
		}
		
		m_pTilesSprite->paintFrame(g, FRAME_TILE_DEAD_BRICK, xRender, yRender);
	}
	else
	{
		m_pOtherSprite->paintMAnim(g, m_manimBlink, xRender, yRender);
	}
}

void QuestionBrick::update()
{
	assert(m_isLive);

	if (!m_isOpened)
	{
		m_pOtherSprite->updateMAnim(m_manimBlink);
	}
	else
	{
		if (m_gift == COIN)
		{
			if (m_dyCoinSpinner < MAX_DY_COIN_SPINNER)
			{
				m_dyCoinSpinner += DY_COIN_SPINNER_STEP;
			}
		}
	}
}

void QuestionBrick::solveCollision()
{
	assert(m_isLive);
	
	for (int i = 0; i < m_collObjsNumber; i++) if (m_pCollInfosList[i].m_isCollision)
	{
		const GameObject* other = m_ppCollObjsList[i];
		CollisionInfo& info = m_pCollInfosList[i];

		switch ( other->getKind() )
		{
			case KIND_PLAYER:
			{
				switch (info.m_edgeResult)
				{
					case EDGE_BOTTOM:
					{
						const int k_epsilon = m_width / 4;
						if (info.m_length > k_epsilon)
						{
							// TODO
							if (!m_isOpened)
							{
								switch (m_gift)
								{
									case COIN:
									{
										m_pOtherSprite->resetMAnim(m_manimCoinSpinner);
										m_dyCoinSpinner = 0;
										break;
									}
									case POWER_UP:
									{
										Player* pPlayer = (Player*)other;
										Mushroom* pMushroom = GameObjectPool<Mushroom>::getInstance()->getFreeObj();
										pMushroom->setLocation(m_px, m_py);
										pMushroom->startLife();
										break;
									}
								}

								m_isOpened = true;

								InvisibleKiller* pKiller = GameObjectPool<InvisibleKiller>::getInstance()->getFreeObj();
								pKiller->setBound( m_px, m_py, m_width, m_height );
								pKiller->setMaxDY(m_height >> 3);
								pKiller->startLife();
							}

						}
						break;
					}
				} // switch (info.m_edgeResult)

				break;
			} // case KIND_PLAYER:
		} // switch ( other->getKind() )
	}

	clearCollisionList();
}

bool QuestionBrick::canCollideWith(const GameObject* other) const
{
	return true;
}
