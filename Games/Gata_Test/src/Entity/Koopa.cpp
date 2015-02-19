#include "Koopa.h"

#include "../ImageManager.h"

#include "../World.h"

#define KOOPA_WIDTH		32
#define KOOPA_HEIGHT	48

#define KOOPA_VX				3
#define KOOPA_VX_FLYING			5
#define KOOPA_NON_HEAD_VX		19
#define KOOPA_VY				16
#define KOOPA_VY_FLYING			22
#define KOOPA_GRAVITY			2
#define KOOPA_GRAVITY_FLYING	2

#define KOOPA_REBORN_FRAME_BEGIN		187
#define KOOPA_REBORN_FRAME_END			(KOOPA_REBORN_FRAME_BEGIN + 38)

Koopa::Koopa() : Entity(KIND_ENEMY_KOOPA)
{
	m_pSprite = SpriteManager::getInstance()->getRes(SPRITE_enemies_myspr);
	m_pSprite->setImage( ImageManager::getInstance()->getRes(IMAGE_enemies_tga) );

	m_manimWalkingLeft = -1;
	m_manimWalkingRight = -1;

	m_manimFlyingLeft = -1;
	m_manimFlyingRight = -1;

	m_manimReborn = -1;

	setSize(KOOPA_WIDTH, KOOPA_HEIGHT);
	setHasCameraTrigger(true);

	setColor(GREEN);
}

Koopa::~Koopa()
{
	unmapAllMAnims();
}

void Koopa::unmapAllMAnims()
{
	if (m_manimWalkingLeft > -1) m_pSprite->unmapMAnim(m_manimWalkingLeft);
	if (m_manimWalkingRight > -1) m_pSprite->unmapMAnim(m_manimWalkingRight);

	if (m_manimFlyingLeft > -1) m_pSprite->unmapMAnim(m_manimFlyingLeft);
	if (m_manimFlyingRight > -1) m_pSprite->unmapMAnim(m_manimFlyingRight);

	if (m_manimReborn > -1) m_pSprite->unmapMAnim(m_manimReborn);
}

void Koopa::setColor(Color color)
{
	unmapAllMAnims();

	m_color = color;

	switch (m_color)
	{
	case RED:
		m_manimWalkingLeft = m_pSprite->mapAnim(ANIM_KOOPA_RED_WALKING_LEFT);
		m_manimWalkingRight = m_pSprite->mapAnim(ANIM_KOOPA_RED_WALKING_RIGHT);

		m_manimFlyingLeft = m_pSprite->mapAnim(ANIM_KOOPA_RED_FLYING_LEFT);
		m_manimFlyingRight = m_pSprite->mapAnim(ANIM_KOOPA_RED_FLYING_RIGHT);

		m_manimReborn = m_pSprite->mapAnim(ANIM_KOOPA_RED_REBORN);

		m_frameNonHead = FRAME_KOOPA_RED_NON_HEAD;
		m_frameThrowing = FRAME_KOOPA_RED_THROWING;
		break;

	case GREEN:
		m_manimWalkingLeft = m_pSprite->mapAnim(ANIM_KOOPA_GREEN_WALKING_LEFT);
		m_manimWalkingRight = m_pSprite->mapAnim(ANIM_KOOPA_GREEN_WALKING_RIGHT);

		m_manimFlyingLeft = m_pSprite->mapAnim(ANIM_KOOPA_GREEN_FLYING_LEFT);
		m_manimFlyingRight = m_pSprite->mapAnim(ANIM_KOOPA_GREEN_FLYING_RIGHT);

		m_manimReborn = m_pSprite->mapAnim(ANIM_KOOPA_GREEN_REBORN);

		m_frameNonHead = FRAME_KOOPA_GREEN_NON_HEAD;
		m_frameThrowing = FRAME_KOOPA_GREEN_THROWING;
		break;

	case BLUE:
		m_manimWalkingLeft = m_pSprite->mapAnim(ANIM_KOOPA_BLUE_WALKING_LEFT);
		m_manimWalkingRight = m_pSprite->mapAnim(ANIM_KOOPA_BLUE_WALKING_RIGHT);

		m_manimFlyingLeft = m_pSprite->mapAnim(ANIM_KOOPA_BLUE_FLYING_LEFT);
		m_manimFlyingRight = m_pSprite->mapAnim(ANIM_KOOPA_BLUE_FLYING_RIGHT);

		m_manimReborn = m_pSprite->mapAnim(ANIM_KOOPA_BLUE_REBORN);

		m_frameNonHead = FRAME_KOOPA_BLUE_NON_HEAD;
		m_frameThrowing = FRAME_KOOPA_BLUE_THROWING;
		break;
	}
}

void Koopa::render(gata::graphic::Graphic* g)
{
	int xRender = m_px - m_pWorld->currentCameraX();
	int yRender = m_py - m_pWorld->currentCameraY();

	switch (m_state)
	{
	case WING:
		m_pSprite->paintAndUpdateMAnim(g, m_isFacingRight ? m_manimFlyingRight : m_manimFlyingLeft, xRender, yRender);
		break;
	case HEAD:
		m_pSprite->paintAndUpdateMAnim(g, m_isFacingRight ? m_manimWalkingRight : m_manimWalkingLeft, xRender, yRender);
		break;
	case NON_HEAD:
		if (m_nonHeadFrameCount < KOOPA_REBORN_FRAME_BEGIN)
			m_pSprite->paintFrame(g, m_frameNonHead, xRender, yRender);
		else
			m_pSprite->paintAndUpdateMAnim(g, m_manimReborn, xRender, yRender);
		break;
	case THROWING:
		m_pSprite->paintFrame(g, m_frameThrowing, xRender, yRender);
		break;
	}
}

void Koopa::update()
{
	if ( m_px + m_width <= 0 || m_px >= m_pWorld->map().totalWidth() || m_py >= m_pWorld->map().totalHeight() )
	{
		setLive(false);
		return;
	}

	switch (m_state)
	{
	case WING:
		break;
	case HEAD:
		break;
	case NON_HEAD:
		m_nonHeadFrameCount++;
		if (m_nonHeadFrameCount >= KOOPA_REBORN_FRAME_END)
		{
			// Reborn
			m_vx = m_isFacingRight ? KOOPA_VX : -KOOPA_VX;
			m_state = HEAD;
		}
		break;
	case THROWING:
		if ( m_py >= m_pWorld->map().totalHeight() )
		{
			setLive(false);
			return;
		}
		break;
	}

	if (!m_vy && checkFalling() && m_state != WING)
	{
		m_vy = KOOPA_VY;
		m_ay = KOOPA_GRAVITY;
	}

	Entity::updateMovement();
}

void Koopa::solveCollision()
{
	if (m_state == THROWING) { clearCollisionList(); return; }

	for (int i = 0; i < m_collObjsNumber; i++) if (m_pCollInfosList[i].m_isCollision)
	{
		const GameObject* other = m_ppCollObjsList[i];
		CollisionInfo& info = m_pCollInfosList[i];
		int kind = other->getKind();

		switch (kind)
		{
			//--------------------------------------------------------------------------------------------------
			case KIND_PLAYER:
			{
				m_px = info.m_newX;
				m_py = info.m_newY;

				if (info.m_edgeResult == EDGE_TOP)
				{
					if (m_state == WING)
					{
						m_vx = m_isFacingRight ? KOOPA_VX : -KOOPA_VX;
						m_state = HEAD;
					}
					else if (m_state == HEAD)
					{
						m_nonHeadFrameCount = 0;
						m_ax = m_vx = m_ay = m_vy = 0;
						m_state = NON_HEAD;
					}
					else if (m_state == NON_HEAD)
					{
						if (m_vx == 0)
						{
							if ( (other->px() >> 1) < (m_px >> 1) )
							{
								m_vx = KOOPA_NON_HEAD_VX;
								m_isFacingRight = true;
							}
							else
							{
								m_vx = -KOOPA_NON_HEAD_VX;
								m_isFacingRight = false;
							}
						}
						else
						{
							m_vx = 0;
						}
					}
				}
				else if ( this->isIdle() )
				{
					if ( info.m_edgeResult == EDGE_LEFT )
					{
						m_vx = KOOPA_NON_HEAD_VX;
						m_isFacingRight = true;
					}
					else if ( info.m_edgeResult == EDGE_RIGHT )
					{
						m_vx = -KOOPA_NON_HEAD_VX;
						m_isFacingRight = false;
					}
				}
				break;
			} // case KIND_PLAYER:

			//--------------------------------------------------------------------------------------------------
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
						if (m_state == WING)
						{
							m_ax = 0;
							m_vx = m_isFacingRight ? KOOPA_VX_FLYING : -KOOPA_VX_FLYING;
							m_ay = KOOPA_GRAVITY_FLYING;
							m_vy = -KOOPA_VY_FLYING;
						}
						else
						{
							m_ay = m_vy = 0;
						}
						break;
					}

					case EDGE_LEFT:
					{
						m_px = other->px() + other->width();
						if (m_vx)
						{
							m_vx = m_state == NON_HEAD ? KOOPA_NON_HEAD_VX : KOOPA_VX;
						}
						m_isFacingRight = true;
						break;
					}

					case EDGE_RIGHT:
					{
						m_px = other->px() - m_width;
						if (m_vx)
						{
							m_vx = m_state == NON_HEAD ? -KOOPA_NON_HEAD_VX : -KOOPA_VX;
						}
						m_isFacingRight = false;
						break;
					}
				} // switch (info.m_edgeResult)

				break;
			} // case

			//--------------------------------------------------------------------------------------------------
			case KIND_ENEMY_GOOMBA:
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
						if ( !this->isGliding() )
						{
							m_vy = -KOOPA_VY;
							m_ay = KOOPA_GRAVITY;
							m_state = THROWING;
							break;
						}
					}
					else
					{
						if ( this->isGliding() )
						{
							break;
						}
					}
				}

				if (	(kind == KIND_ENEMY_GOOMBA || kind == KIND_OTHER_MUSHROOM) &&
						(info.m_edgeResult == EDGE_LEFT || info.m_edgeResult == EDGE_RIGHT)		)
				{
					if ( this->isGliding() )
					{
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
						m_vy = -KOOPA_VY;
						m_ay = KOOPA_GRAVITY;
						break;
					}

					case EDGE_LEFT:
					{
						if (m_vx < 0)
						{
							if (m_state == NON_HEAD) m_vx = KOOPA_NON_HEAD_VX;
							else if (m_state == WING) m_vx = KOOPA_VX_FLYING;
							else m_vx = KOOPA_VX;
							m_isFacingRight = true;
						}
						break;
					}

					case EDGE_RIGHT:
					{
						if (m_vx > 0)
						{
							if (m_state == NON_HEAD) m_vx = -KOOPA_NON_HEAD_VX;
							else if (m_state == WING) m_vx = -KOOPA_VX_FLYING;
							else m_vx = -KOOPA_VX;
							m_isFacingRight = false;
						}
						break;
					}
				} // switch (info.m_edgeResult)

				break;
			} // case

			//--------------------------------------------------------------------------------------------------
			case KIND_OTHER_INVISIBLE_KILLER:
			{
				m_vy = -KOOPA_VY;
				m_ay = KOOPA_GRAVITY;
				m_state = THROWING;
				break;
			} // case

		} // switch (kind)

	} // for

	clearCollisionList();
}

bool Koopa::canCollideWith(const GameObject* other) const
{
	if (m_state == THROWING) return false;

	/*switch (other->getKind())
	{
		case KIND_OTHER_PARTICLE:
			return false;
	}*/

	return true;
}

void Koopa::startLife()
{
	if (!m_canStartLife) return;
	m_canStartLife = false;

	m_isFacingRight = m_originalFacingRight;

	m_px = m_originalX;
	m_py = m_originalY;

	m_prevpx = m_px;
	m_prevpy = m_py;

	if (m_originalHasWing)
	{
		m_ax = 0;
		m_vx = m_isFacingRight ? KOOPA_VX : -KOOPA_VX;

		m_ay = KOOPA_GRAVITY;
		m_vy = -KOOPA_VY;

		m_state = WING;
	}
	else
	{
		m_ax = m_ay = 0;
		m_vx = m_isFacingRight ? KOOPA_VX : -KOOPA_VX;
		m_vy = 0;

		m_state = HEAD;
	}

	setLive(true);
}
