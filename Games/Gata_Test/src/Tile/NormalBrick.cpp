#include "NormalBrick.h"

#include "../SpriteManager.h"
#include "../ImageManager.h"

#include"../World.h"

#include "../GameObjectPool.h"

#include "../Entity/Particle.h"
#include "../Entity/InvisibleKiller.h"

NormalBrick::NormalBrick(int layer, int col, int row, World* pWorld) : TiledObject(layer, col, row, pWorld)
{
	m_kind = KIND_TILE_NORMAL_BRICK;

	m_pSprite = SpriteManager::getInstance()->getRes(SPRITE_tiles_myspr);
	m_pSprite->setImage(ImageManager::getInstance()->getRes(IMAGE_tiles_tga));
}

void NormalBrick::render(gata::graphic::Graphic* g)
{
	assert(m_isLive);
	int xRender = m_px - m_pWorld->currentCameraX();
	int yRender = m_py - m_pWorld->currentCameraY();
	m_pSprite->paintFrame(g, FRAME_TILE_NORMAL_BRICK, xRender, yRender);
}

void NormalBrick::update()
{
	assert(m_isLive);
}

void NormalBrick::solveCollision()
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
							Particle* pParticle = GameObjectPool<Particle>::getInstance()->getFreeObj();
							pParticle->setLocation( m_px + (m_width >> 1), m_py + (m_height >> 1) );
							pParticle->startLife();

							InvisibleKiller* pKiller = GameObjectPool<InvisibleKiller>::getInstance()->getFreeObj();
							pKiller->setBound( m_px, m_py, m_width, m_height );
							pKiller->setMaxDY(m_height >> 3);
							pKiller->startLife();

							m_pWorld->decLayerMark( colIndex(), rowIndex() );
						}
						break;
					}
				} // switch (info.m_edgeResult)
			} // case KIND_PLAYER:
		} // switch ( other->getKind() )
	}

	clearCollisionList();
}

bool NormalBrick::canCollideWith(const GameObject* other) const
{
	return true;
}
