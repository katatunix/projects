#include "World.h"

#include <stdio.h>

#include "CGame.h"

#include "GameObjectPool.h"

#include "Entity/Player.h"
#include "Entity/Goomba.h"
#include "Entity/Koopa.h"
#include "Entity/Particle.h"
#include "Entity/InvisibleKiller.h"
#include "Entity/Mushroom.h"

#include "Tile/Background.h"

#include "Tile/NormalBrick.h"
#include "Tile/QuestionBrick.h"


#include <gata/utils/MyUtils.h>

using namespace gata::utils;

#define MAX_PLAYERS_NUMBER				1
#define MAX_GOOMBAS_NUMBER				32
#define MAX_KOOPAS_NUMBER				32
#define MAX_MUSHROOMS_NUMBER			32
#define MAX_PARTICLES_NUMBER			16
#define MAX_INVISIBLE_KILLERS_NUMBER	8

World::World() :
		m_pPlayer(0),
		m_isLeftHolding(false),
		m_isRightHolding(false),
		m_isJumpHolding(false),
		m_isFastHolding(false)
{
	new GameObjectPool<Player>(MAX_PLAYERS_NUMBER);

	new GameObjectPool<Goomba>(MAX_GOOMBAS_NUMBER);
	new GameObjectPool<Koopa>(MAX_KOOPAS_NUMBER);

	new GameObjectPool<Mushroom>(MAX_MUSHROOMS_NUMBER);
	
	new GameObjectPool<Particle>(MAX_PARTICLES_NUMBER);
	new GameObjectPool<InvisibleKiller>(MAX_INVISIBLE_KILLERS_NUMBER);

	Entity* p = Entity::head();
	while (p)
	{
		p->setWorld(this);
		p = Entity::next();
	}

	for (int k = 0; k < MAX_MAP_LAYERS_NUMBER; k++)
		for (int i = 0; i < MAX_MAP_COLS_NUMBER; i++)
			for (int j = 0; j < MAX_MAP_ROWS_NUMBER; j++)
				m_pTiledObjectsTable[k][i][j] = 0;
}

World::~World()
{
	unload();

	GameObjectPool<InvisibleKiller>::freeInstance();
	GameObjectPool<Particle>::freeInstance();
	
	GameObjectPool<Mushroom>::freeInstance();
	
	GameObjectPool<Koopa>::freeInstance();
	GameObjectPool<Goomba>::freeInstance();

	GameObjectPool<Player>::freeInstance();
}

void World::setCamera(int x, int y, int width, int height)
{
	m_currentCameraX = x;
	m_currentCameraY = y;
	m_cameraWidth = width;
	m_cameraHeight = height;
}

void World::setCameraPosition(int x, int y)
{
	m_currentCameraX = x;
	m_currentCameraY = y;
}

void World::setCameraSize(int width, int height)
{
	m_cameraWidth = width;
	m_cameraHeight = height;
}

TiledObject* World::createBackground(int data, int layer, int col, int row)
{
	TiledObject* p = new Background(layer, col, row, this, data);

	p->setBound( col * m_map.tileWidth(), row * m_map.tileHeight(), m_map.tileWidth(), m_map.tileHeight() );
	p->setprevpx(p->px());
	p->setprevpy(p->py());
	p->setLive(true);

	return p;
}

TiledObject* World::createTiledObject(int data, int layer, int col, int row)
{
	TiledObject* p = 0;
	switch (data)
	{
		case FRAME_TILE_NORMAL_BRICK:
			p = new NormalBrick(layer, col, row, this);
			break;
		case FRAME_TILE_QUESTION_BRICK:
			p = new QuestionBrick(layer, col, row, this);
			break;
		default:
			assert(0);
			break;
	}

	p->setBound( col * m_map.tileWidth(), row * m_map.tileHeight(), m_map.tileWidth(), m_map.tileHeight() );
	p->setprevpx(p->px());
	p->setprevpy(p->py());
	p->setLive(true);

	return p;
}

void World::load(const char* szFilePath)
{
	// Map
	m_map.load(szFilePath);

	TileLayer* pTileLayer0 = (TileLayer*)(m_map.getLayer("Background"));
	int x0 = pTileLayer0->firstColIndex();
	int y0 = pTileLayer0->firstRowIndex();
	int w0 = pTileLayer0->colsNumber();
	int h0 = pTileLayer0->rowsNumber();

	TileLayer* pTileLayer1 = (TileLayer*)(m_map.getLayer("TiledObject"));
	int x1 = pTileLayer1->firstColIndex();
	int y1 = pTileLayer1->firstRowIndex();
	int w1 = pTileLayer1->colsNumber();
	int h1 = pTileLayer1->rowsNumber();

	int colsNumber = m_map.colsNumber();
	int rowsNumber = m_map.rowsNumber();

	for (int i = 0; i < colsNumber; i++)
	{
		for (int j = 0; j < rowsNumber; j++)
		{
			m_ppMark[i][j] = -1;

			if (i >= x0 && i < x0 + w0 && j >= y0 && j < y0 + h0)
			{
				int data = pTileLayer0->getData(i - x0, j - y0);
				if (data > 0)
				{
					m_pTiledObjectsTable[0][i][j] = createBackground(data, 0, i, j);
					m_ppMark[i][j] = 0;
				}
			}
			if (i >= x1 && i < x1 + w1 && j >= y1 && j < y1 + h1)
			{
				int data = pTileLayer1->getData(i - x1, j - y1);
				if (data > 0)
				{
					m_pTiledObjectsTable[1][i][j] = createTiledObject(data, 1, i, j);
					m_ppMark[i][j] = 1;
				}
			}
		}
	}

	ObjectLayer* pEntityLayer = (ObjectLayer*)(m_map.getLayer("Entity"));
	int count = pEntityLayer->objectsNumber();

	for (int i = 0; i < count; i++)
	{
		const ObjectMapData& data = pEntityLayer->getObjectData(i);
		if ( !strcmp(data.szType, "Player") )
		{
			m_pPlayer = GameObjectPool<Player>::getInstance()->getFreeObj();
			
			if ( !strcmp(data.szName, "mario") )
			{
				m_pPlayer->setBrother(Player::MARIO);
			}
			else
			{
				assert(0);
				m_pPlayer->setBrother(Player::LUGI);
			}

			m_pPlayer->setOriginal(data.x, data.y);
			m_pPlayer->setOrinigalFacingRight( ! data.properties["isOriginalFacingRight"].asString().compare("true") );
		}
		else if ( !strcmp(data.szType, "Goomba") )
		{
			Goomba* p = GameObjectPool<Goomba>::getInstance()->getWildObj();
			p->setOrinigalFacingRight( ! data.properties["isOriginalFacingRight"].asString().compare("true") );
			p->setOriginal(data.x, data.y);
			
			p->setHasOriginalData(true);
		}
		else if ( !strcmp(data.szType, "Koopa") )
		{
			Koopa* p = GameObjectPool<Koopa>::getInstance()->getWildObj();
			p->setOrinigalFacingRight( ! data.properties["isOriginalFacingRight"].asString().compare("true") );
			p->setOriginalHasWing( ! data.properties["isOriginalHasWing"].asString().compare("true") );
			p->setOriginal(data.x, data.y);
			
			p->setHasOriginalData(true);
		}
	}
	
	//
	m_pPlayer->startLife();
}

void World::unload()
{
	for (int k = 0; k < MAX_MAP_LAYERS_NUMBER; k++)
		for (int i = 0; i < MAX_MAP_COLS_NUMBER; i++)
			for (int j = 0; j < MAX_MAP_ROWS_NUMBER; j++)
				SAFE_DEL( m_pTiledObjectsTable[k][i][j] );
}

void World::update()
{
	int colsNumber = m_map.colsNumber();
	int rowsNumber = m_map.rowsNumber();

	// Update Entities
	// todo: only update entities which are currently inside camera view?
	Entity* pEntity = Entity::head();
	while (pEntity)
	{
		if (pEntity->isLive())
		{
			pEntity->update();
		}
		pEntity = Entity::next();
	}

	// Update Tiles
	// todo: only update tiles which are currently inside camera view?
	for (int i = 0; i < colsNumber; i++) for (int j = 0; j < rowsNumber; j++)
	{
		TiledObject* p = getTiledObject(i, j);
		if (p && p->isLive()) p->update();
	}
	
	// Check collision
	while (checkAndSolveCollision()) { }

	// Update camera's position base on player's position
	//
	int xCam = m_pPlayer->px() + m_pPlayer->width()/2 - (m_cameraWidth >> 1);
	if (xCam < 0) xCam = 0;

	int temp = m_map.totalWidth() - m_cameraWidth;
	if (xCam > temp) xCam = temp;

	//
	int yCam = m_pPlayer->py() + m_pPlayer->height()/2 - (m_cameraHeight >> 1);
	if (yCam < 0) yCam = 0;

	temp = m_map.totalHeight() - m_cameraHeight;
	if (yCam > temp) yCam = temp;

	//
	setCameraPosition(xCam, yCam);

	// When camera's position is changed, something is happend
	pEntity = Entity::head();
	while (pEntity)
	{
		if ( !pEntity->isLive() && pEntity->hasOriginalData() && pEntity->hasCameraTrigger() )
		{
			if (xCam < pEntity->originalX() + pEntity->width() && pEntity->originalX() < xCam + m_cameraWidth)
			{
				// Check collision before start life
				int x = pEntity->originalX();
				int y = pEntity->originalY();
				int width = pEntity->width();
				int height = pEntity->height();

				bool isCollision = false;
				Entity* pEntity2 = Entity::head2();
				while (pEntity2)
				{
					if ( pEntity2->isLive() )
					{
						if (isRectCollision(
								x, y, width, height,
								pEntity2->px(), pEntity2->py(), pEntity2->width(), pEntity2->height()
						))
						{
							isCollision = true;
							break;
						}
					}
					pEntity2 = Entity::next2();
				}
				if (!isCollision)
				{
					pEntity->startLife();
				}
			}
			else
			{
				pEntity->setCanStartLife(true);
			}
		}
		pEntity = Entity::next();
	}
}

bool World::checkAndSolveCollision()
{
	int colsNumber = m_map.colsNumber();
	int rowsNumber = m_map.rowsNumber();
	int w = m_map.tileWidth();
	int h = m_map.tileHeight();
	int edgeResult_dst, distSqr_src, distSqr_dst, offset_dst, length, x_src_new, y_src_new, x_dst_new, y_dst_new;

	// 1. Between Entities and Tiles
	Entity* pEntity = Entity::head();
	while (pEntity)
	{
		if ( pEntity->isLive() )
		{
			int col1 = pEntity->px() / w;
			if (col1 < 0) col1 = 0;
			if (col1 >= colsNumber) col1 = colsNumber - 1;

			int col2 = ( pEntity->px() + pEntity->width() - 1 ) / w;
			if (col2 < 0) col2 = 0;
			if (col2 >= colsNumber) col2 = colsNumber - 1;

			int row1 = pEntity->py() / h;
			if (row1 < 0) row1 = 0;
			if (row1 >= rowsNumber) row1 = rowsNumber - 1;

			int row2 = ( pEntity->py() + pEntity->height() - 1 ) / h;
			if (row2 < 0) row2 = 0;
			if (row2 >= rowsNumber) row2 = rowsNumber - 1;

			for (int i = col1; i <= col2; i++) for (int j = row1; j <= row2; j++)
			{
				TiledObject* pTile = getTiledObject(i, j);
				if ( pTile && pTile->isLive() && pTile->canCollideWith(pEntity) && pEntity->canCollideWith(pTile) )
				{
					if ( GameObject::checkCollision(pEntity, pTile,
							edgeResult_dst, distSqr_src, distSqr_dst, offset_dst, length,
							x_src_new, y_src_new, x_dst_new, y_dst_new) )
					{
						CollisionInfo info;
						info.m_isCollision = true;
						info.m_edgeResult = edgeResult_dst;
						info.m_distSqr = distSqr_dst;
						info.m_offset = offset_dst;
						info.m_length = length;
						info.m_newX = x_dst_new;
						info.m_newY = y_dst_new;

						pTile->addCollision(pEntity, info);

						info.m_edgeResult = -info.m_edgeResult;
						info.m_offset = -info.m_offset;
						info.m_distSqr = distSqr_src;
						info.m_newX = x_src_new;
						info.m_newY = y_src_new;

						pEntity->addCollision(pTile, info);
					}
				}
			}
		}
		
		pEntity = Entity::next();
	}

	// 2. Between Entities and Entities
	gata::core::LinkedNode<Entity*>* pEntityNode1 = Entity::firstNode();
	while (pEntityNode1)
	{
		Entity* pEntity1 = pEntityNode1->value;
		if (pEntity1->isLive())
		{
			gata::core::LinkedNode<Entity*>* pEntityNode2 = pEntityNode1->next;
			while (pEntityNode2)
			{
				Entity* pEntity2 = pEntityNode2->value;
				if ( pEntity2->isLive() && pEntity1->canCollideWith(pEntity2) && pEntity2->canCollideWith(pEntity1) )
				{
					if ( GameObject::checkCollision(pEntity1, pEntity2,
								edgeResult_dst, distSqr_src, distSqr_dst, offset_dst, length,
								x_src_new, y_src_new, x_dst_new, y_dst_new) )
					{
						CollisionInfo info;
						info.m_isCollision = true;
						info.m_edgeResult = edgeResult_dst;
						info.m_distSqr = distSqr_dst;
						info.m_offset = offset_dst;
						info.m_length = length;
						info.m_newX = x_dst_new;
						info.m_newY = y_dst_new;

						pEntity2->addCollision(pEntity1, info);

						info.m_edgeResult = -info.m_edgeResult;
						info.m_offset = -info.m_offset;
						info.m_distSqr = distSqr_src;
						info.m_newX = x_src_new;
						info.m_newY = y_src_new;

						pEntity1->addCollision(pEntity2, info);
					}
				}
				pEntityNode2 = pEntityNode2->next;
			}
		}
		pEntityNode1 = pEntityNode1->next;
	}

	reduceCollisionList();

	// Solve collision
	bool isContinue = false;
	
	for (int i = 0; i < colsNumber; i++) for (int j = 0; j < rowsNumber; j++)
	{
		TiledObject* p = getTiledObject(i, j);
		if ( p && p->isLive() && p->hasCollision() )
		{
			isContinue = true;
			p->solveCollision();
		}
	}
	pEntity = Entity::head();
	while (pEntity)
	{
		if ( pEntity->isLive() && pEntity->hasCollision() )
		{
			isContinue = true;
			pEntity->solveCollision();
		}
		pEntity = Entity::next();
	}

	return isContinue;
}

void World::reduceCollisionList()
{
	int colsNumber = m_map.colsNumber();
	int rowsNumber = m_map.rowsNumber();

	for (int i = 0; i < colsNumber; i++) for (int j = 0; j < rowsNumber; j++)
	{
		TiledObject* p = getTiledObject(i, j);
		if ( p && p->isLive() && p->hasCollision() )
		{
			p->reduceCollisionList();
		}
	}
	Entity* pEntity = Entity::head();
	while (pEntity)
	{
		if ( pEntity->isLive() && pEntity->hasCollision() )
		{
			pEntity->reduceCollisionList();
		}
		pEntity = Entity::next();
	}
}

void World::render(gata::graphic::Graphic* g)
{
	// Render Tiles
	int colsNumber = m_map.colsNumber();
	int rowsNumber = m_map.rowsNumber();

	int leftCol = m_currentCameraX / m_map.tileWidth(); assert(leftCol < colsNumber);
	int rightCol = (m_currentCameraX + m_cameraWidth - 1) / m_map.tileWidth(); assert(rightCol < colsNumber);

	for (int i = leftCol; i <= rightCol; i++) for (int j = 0; j < rowsNumber; j++)
	{
		TiledObject* p = getTiledObject(i, j);
		if (p)
		{
			p->render(g_pGraphic);
		}
	}

	// Render Entities
	Entity* p = Entity::head();
	while (p)
	{
		if (p->isLive() && p->px() + p->width() >= m_currentCameraX && p->px() < m_currentCameraX + m_cameraWidth)
		{
			
			p->render(g_pGraphic);
			
		}
		p = Entity::next();
	}

	
}

void World::decLayerMark(int i, int j)
{
	assert(m_ppMark[i][j] > 0);
	m_ppMark[i][j]--;
}

void World::getPlayerPos(int& x, int& y)
{
	x = m_pPlayer->px();
	y = m_pPlayer->py();
}

int World::getLiveEntitiesCount()
{
	int count = 0;
	Entity* p = Entity::head();
	while (p)
	{
		if (p->isLive()) count++;
		p = Entity::next();
	}
	return count;
}
