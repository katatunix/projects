#include "Goomba.h"

#include "../ImageManager.h"

#include "../World.h"
#include "Koopa.h"

#define GOOMBA_VX			3
#define GOOMBA_VY			16
#define GOOMBA_GRAVITY		5

#define GOOMBA_MAX_DYING_FRAME 15

Goomba::Goomba() : Entity(KIND_ENEMY_GOOMBA)
{
	m_pSprite = SpriteManager::getInstance()->getRes(SPRITE_enemies_myspr);
	m_pSprite->setImage( ImageManager::getInstance()->getRes(IMAGE_enemies_tga) );
	m_manim = -1;

	setSize(32, 32);
	setHasCameraTrigger(true);

	setColor(RED);
}

Goomba::~Goomba()
{
	if (m_manim > -1) m_pSprite->unmapMAnim(m_manim);
}

void Goomba::setColor(Color color)
{
	if (m_manim > -1) m_pSprite->unmapMAnim(m_manim);
	m_color = color;

	switch (m_color)
	{
	case RED:
		m_manim = m_pSprite->mapAnim(ANIM_GOOMBA_RED);
		m_frameDying = FRAME_GOOMBA_RED_DYING;
		m_frameThrowing = FRAME_GOOMBA_RED_THROWING;
		break;
	case BLUE:
		m_manim = m_pSprite->mapAnim(ANIM_GOOMBA_BLUE);
		m_frameDying = FRAME_GOOMBA_BLUE_DYING;
		m_frameThrowing = FRAME_GOOMBA_BLUE_THROWING;
		break;
	case GRAY:
		m_manim = m_pSprite->mapAnim(ANIM_GOOMBA_GRAY);
		m_frameDying = FRAME_GOOMBA_GRAY_DYING;
		m_frameThrowing = FRAME_GOOMBA_GRAY_THROWING;
		break;
	}
}

void Goomba::render(gata::graphic::Graphic* g)
{
	int xRender = m_px - m_pWorld->currentCameraX();
	int yRender = m_py - m_pWorld->currentCameraY();

	

	if (m_state == DYING)
	{
		m_pSprite->paintFrame(g, m_frameDying, xRender, yRender);
		return;
	}

	

	if (m_state == LIVING)
	{
		assert(m_manim > -1);
		m_pSprite->paintAndUpdateMAnim(g, m_manim, xRender, yRender);
		return;
	}

	

	m_pSprite->paintFrame(g, m_frameThrowing, xRender, yRender);
	
}

void Goomba::update()
{
	if ( m_px + m_width <= 0 || m_px >= m_pWorld->map().totalWidth() || m_py >= m_pWorld->map().totalHeight() )
	{
		setLive(false);
		return;
	}

	if (m_state == DYING)
	{
		m_dyingFrameCount++;
		if (m_dyingFrameCount >= GOOMBA_MAX_DYING_FRAME)
		{
			setLive(false);
		}
		return;
	}

	if (m_state == THROWING)
	{
		if ( m_py >= m_pWorld->map().totalHeight() )
		{
			setLive(false);
			return;
		}
	}
	else // LIVING
	{
		if (!m_vy && checkFalling())
		{
			m_vy = GOOMBA_VY;
			m_ay = GOOMBA_GRAVITY;
		}
	}

	Entity::updateMovement();
}

void Goomba::solveCollision()
{
	if (m_state != LIVING) { clearCollisionList(); return; }

	for (int i = 0; i < m_collObjsNumber; i++) if (m_pCollInfosList[i].m_isCollision)
	{
		const GameObject* other = m_ppCollObjsList[i];
		CollisionInfo& info = m_pCollInfosList[i];
		int kind = other->getKind();

		switch (kind)
		{
			//------------------------------------------------------------------------------------------
			case KIND_PLAYER:
			{
				m_px = info.m_newX;
				m_py = info.m_newY;
				if (info.m_edgeResult == EDGE_TOP)
				{
					m_state = DYING;
					m_dyingFrameCount = 0;
				}
				break;
			} // case

			//------------------------------------------------------------------------------------------
			case KIND_TILE_NORMAL_BRICK:
			case KIND_TILE_QUESTION_BRICK:
			case KIND_TILE_BACKGROUND:
			{
				switch (info.m_edgeResult)
				{
					case EDGE_TOP:
					{
						m_py = other->py() + other->height();
						m_vy = 0;
						break;
					}

					case EDGE_BOTTOM:
					{
						m_py = other->py() - m_height;
						m_ay = m_vy = 0;
						break;
					}

					case EDGE_LEFT:
					{
						m_px = other->px() + other->width();
						m_vx = GOOMBA_VX;
						break;
					}

					case EDGE_RIGHT:
					{
						m_px = other->px() - m_width;
						m_vx = -GOOMBA_VX;
						break;
					}
				} // switch (info.m_edgeResult)

				break;
			} // case

			//------------------------------------------------------------------------------------------
			case KIND_ENEMY_KOOPA:
			case KIND_OTHER_MUSHROOM:
			{
				m_px = info.m_newX;
				m_py = info.m_newY;

				if ( kind == KIND_ENEMY_KOOPA && (info.m_edgeResult == EDGE_LEFT || info.m_edgeResult == EDGE_RIGHT) )
				{
					Koopa* pKoopa = (Koopa*)other;
					if ( pKoopa->isGliding() )
					{
						m_vy = -GOOMBA_VY;
						m_ay = GOOMBA_GRAVITY;
						m_state = THROWING;
						break;
					}
				}

				switch (info.m_edgeResult)
				{
					case EDGE_TOP:
					{
						m_vy = 0;
						break;
					}

					case EDGE_BOTTOM:
					{
						m_vy = -GOOMBA_VY;
						m_ay = GOOMBA_GRAVITY;
						break;
					}

					case EDGE_LEFT:
					{
						if (m_vx < 0) m_vx = GOOMBA_VX;
						break;
					}

					case EDGE_RIGHT:
					{
						if (m_vx > 0) m_vx = -GOOMBA_VX;
						break;
					}
				} // switch (info.m_edgeResult)

				break;
			} // case

			//------------------------------------------------------------------------------------------
			case KIND_OTHER_INVISIBLE_KILLER:
			{
				m_vy = -GOOMBA_VY;
				m_ay = GOOMBA_GRAVITY;
				m_state = THROWING;
				break;
			} // case

		} // switch (other->getKind())

	} // for

	clearCollisionList();
}

bool Goomba::canCollideWith(const GameObject* other) const
{
	if (m_state != LIVING) return false;

	switch (other->getKind())
	{
		case KIND_ENEMY_GOOMBA:
			return false;
	}
	return true;
}

void Goomba::startLife()
{
	if (!m_canStartLife) return;
	m_canStartLife = false;

	m_isFacingRight = m_originalFacingRight;

	m_ax = m_ay = 0;

	m_vx = m_isFacingRight ? GOOMBA_VX : -GOOMBA_VX;
	m_vy = 0;

	m_px = m_originalX;
	m_py = m_originalY;

	m_prevpx = m_px;
	m_prevpy = m_py;

	m_state = LIVING;

	setLive(true);
}
