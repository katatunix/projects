#include "Particle.h"

#include "../ImageManager.h"

#include "../World.h"

Particle::Particle() : Entity(KIND_OTHER_PARTICLE)
{
	m_pSprite = SpriteManager::getInstance()->getRes(SPRITE_other_myspr);
	m_pSprite->setImage( ImageManager::getInstance()->getRes(IMAGE_other_tga) );
}

Particle::~Particle()
{
	
}

void Particle::setColor(Color color)
{
	m_color = color;
}

void Particle::render(gata::graphic::Graphic* g)
{
	int xRender = m_px - m_pWorld->currentCameraX();
	int yRender = m_py - m_pWorld->currentCameraY();

	int frameWidth = m_pSprite->getFrameWidth(FRAME_RED_PARTICLE_RIGHT);

	m_pSprite->paintFrame(g, FRAME_RED_PARTICLE_RIGHT, (int)(xRender + m_x1), (int)(yRender + m_y1));
	m_pSprite->paintFrame(g, FRAME_RED_PARTICLE_RIGHT, (int)(xRender + m_x2), (int)(yRender + m_y2));

	m_pSprite->paintFrame(g, FRAME_RED_PARTICLE_RIGHT, (int)(xRender - m_x1 - frameWidth), (int)(yRender + m_y1));
	m_pSprite->paintFrame(g, FRAME_RED_PARTICLE_RIGHT, (int)(xRender - m_x2 - frameWidth), (int)(yRender + m_y2));
}

void Particle::update()
{
	m_vx1 += m_ax1;	m_vy1 += m_ay1;
	m_x1 += m_vx1;	m_y1 += m_vy1;

	m_vx2 += m_ax2;	m_vy2 += m_ay2;
	m_x2 += m_vx2;	m_y2 += m_vy2;

	if (m_y1 >= m_pWorld->cameraHeight())
	{
		setLive(false);
	}
}

void Particle::solveCollision()
{
	clearCollisionList();
}

bool Particle::canCollideWith(const GameObject* other) const
{
	return false;
}

void Particle::startLife()
{
	m_x1 = m_y1 = 0.0f;
	m_x2 = m_y2 = 0.0f;

	m_ax1 = 0.512f;	m_ay1 = 5.12f;
	m_ax2 = 0.512f;	m_ay2 = 5.12f;

	m_vx1 = 0.16f;	m_vy1 = -32.0f;
	m_vx2 = 0.8f;	m_vy2 = -24.0f;

	m_prevpx = m_px;
	m_prevpy = m_py;

	setLive(true);
}
