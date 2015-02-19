#include "Mushroom.h"
#include "Koopa.h"

#include "../ImageManager.h"
#include "../World.h"

#define MUSHROOM_WIDTH			32
#define MUSHROOM_HEIGHT			32

#define MUSHROOM_VX			3
#define MUSHROOM_VY			16
#define MUSHROOM_GRAVITY	5

#define MUSHROOM_RISING_STEP 6

Mushroom::Mushroom() : Entity(KIND_OTHER_MUSHROOM)
{
	m_pSprite = getSprite(SPRITE_other_myspr);
	m_pSprite->setImage( getImage(IMAGE_other_tga) );

	m_color = RED;
	setSize(MUSHROOM_WIDTH, MUSHROOM_HEIGHT);
	setHasCameraTrigger(false);
}

Mushroom::~Mushroom()
{
	
}

void Mushroom::setColor(Color color)
{
	m_color = color;
}

void Mushroom::render(gata::graphic::Graphic* g)
{
	int xRender = m_px - m_pWorld->currentCameraX();
	int yRender = m_py - m_pWorld->currentCameraY();
	int frameId = m_color == RED ? FRAME_MUSHROOM_RED : FRAME_MUSHROOM_GREEN;
	Transform t;
	if (m_state == THROWING) t.append(FLIP_Y);
	m_pSprite->paintFrame(g, frameId, xRender, yRender, &t);
}

void Mushroom::update()
{
	if ( m_px + m_width <= 0 || m_px >= m_pWorld->map().totalWidth() || m_py >= m_pWorld->map().totalHeight() )
	{
		setLive(false);
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
	else if (m_state == RUNNING)
	{
		if (!m_vy && checkFalling())
		{
			m_vy = MUSHROOM_VY;
			m_ay = MUSHROOM_GRAVITY;
		}
	}
	else // RISING
	{
		if (m_risingStartY - m_py >= m_height)
		{
			m_vx = m_isFacingRight ? MUSHROOM_VX : -MUSHROOM_VX;
			m_py = m_risingStartY - m_height;
			m_vy = 0;
			m_state = RUNNING;
		}
	}

	Entity::updateMovement();
}

void Mushroom::solveCollision()
{
	if (m_state == THROWING) { clearCollisionList(); return; }

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
				clearCollisionList();
				setLive(false);
				return;
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
						m_vx = MUSHROOM_VX;
						break;
					}

					case EDGE_RIGHT:
					{
						m_px = other->px() - m_width;
						m_vx = -MUSHROOM_VX;
						break;
					}
				} // switch (info.m_edgeResult)

				break;
			} // case

			//------------------------------------------------------------------------------------------
			case KIND_ENEMY_KOOPA:
			case KIND_ENEMY_GOOMBA:
			case KIND_OTHER_MUSHROOM:
			{
				m_px = info.m_newX;
				m_py = info.m_newY;

				if ( kind == KIND_ENEMY_KOOPA && (info.m_edgeResult == EDGE_LEFT || info.m_edgeResult == EDGE_RIGHT) )
				{
					Koopa* pKoopa = (Koopa*)other;
					if ( pKoopa->isGliding() )
					{
						m_vy = -MUSHROOM_VY;
						m_ay = MUSHROOM_GRAVITY;
						m_state = THROWING;
						break;
					}
				}

				switch (info.m_edgeResult)
				{
					case EDGE_TOP:
					{
						if (m_state != RISING)
							m_vy = 0;
						break;
					}

					case EDGE_BOTTOM:
					{
						m_vy = -MUSHROOM_VY;
						m_ay = MUSHROOM_GRAVITY;
						break;
					}

					case EDGE_LEFT:
					{
						if (m_vx < 0) m_vx = MUSHROOM_VX;
						break;
					}

					case EDGE_RIGHT:
					{
						if (m_vx > 0) m_vx = -MUSHROOM_VX;
						break;
					}
				} // switch (info.m_edgeResult)

				break;
			} // case

			//------------------------------------------------------------------------------------------
			case KIND_OTHER_INVISIBLE_KILLER:
			{
				m_px = info.m_newX;
				m_py = info.m_newY;

				m_vy = -MUSHROOM_VY;
				m_ay = MUSHROOM_GRAVITY;

				if (m_px + m_width / 2 <= other->px() + other->width() / 2)
				{
					if (m_vx > 0) m_vx = -MUSHROOM_VX;
				}
				else
				{
					if (m_vx < 0) m_vx = MUSHROOM_VX;
				}
				break;
			} // case

		} // switch (other->getKind())

	} // for

	clearCollisionList();
}

bool Mushroom::canCollideWith(const GameObject* other) const
{
	if (m_state == THROWING) return false;

	switch (other->getKind())
	{
		case KIND_OTHER_PARTICLE:
			return false;
		case KIND_TILE_QUESTION_BRICK:
			return m_state == RUNNING;
		case KIND_OTHER_INVISIBLE_KILLER:
			return m_state == RUNNING;
	}
	return true;
}

void Mushroom::startLife()
{
	m_isFacingRight = true;// m_originalFacingRight;

	m_ax = m_ay = 0;

	m_vx = 0;
	m_vy = -MUSHROOM_RISING_STEP;

	m_prevpx = m_px;
	m_prevpy = m_py;

	m_risingStartY = m_py;

	m_state = RISING;
	
	setLive(true);
}
