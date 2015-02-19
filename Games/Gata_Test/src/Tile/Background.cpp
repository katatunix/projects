#include "Background.h"

#include "../SpriteManager.h"
#include "../ImageManager.h"

#include"../World.h"

Background::Background(int layer, int col, int row, World* pWorld, int data) :
		TiledObject(layer, col, row, pWorld),
		m_data(data)
{
	m_kind = KIND_TILE_BACKGROUND;

	m_isOpaque = 
		data == FRAME_TILE_GROUND													||
		(data >= FRAME_TILE_VERTICAL_PIPE && data <= FRAME_TILE_VERTICAL_PIPE + 3)	||
		(data >= FRAME_TILE_HORIZON_PIPE && data <= FRAME_TILE_HORIZON_PIPE + 3);

	m_pSrite = SpriteManager::getInstance()->getRes(SPRITE_tiles_myspr);
	m_pSrite->setImage( ImageManager::getInstance()->getRes(IMAGE_tiles_tga) );
}

void Background::render(gata::graphic::Graphic* g)
{
	assert(m_isLive);
	int xRender = m_px - m_pWorld->currentCameraX();
	int yRender = m_py - m_pWorld->currentCameraY();
	m_pSrite->paintFrame(g, m_data, xRender, yRender);
}

void Background::update()
{
	assert(m_isLive);
}

void Background::solveCollision()
{
	assert(m_isLive);
	clearCollisionList();
}

bool Background::canCollideWith(const GameObject* other) const
{
	return m_isOpaque;
}
