#include "Player.h"
#include "../SpriteManager.h"
#include "../ImageManager.h"
#include "../CGame.h"
#include "../World.h"
#include "../Tile/TiledObject.h"

#include "Goomba.h"
#include "Koopa.h"

#define BODY_SMALL_WIDTH			32
#define BODY_SMALL_HEIGHT			32
#define BODY_LARGE_WIDTH			32
#define BODY_LARGE_HEIGHT			64
#define BODY_FLOWER_WIDTH			32
#define BODY_FLOWER_HEIGHT			64

#define AX							3
#define MAX_VX						9
#define MAX_VX_FAST					12
#define GRAVITY						6
#define JUMP_VY						(-45)
#define JUMP_VY_FAST				(-50)

Player::Player() : Entity(KIND_PLAYER)
{
	m_manimRunningRight = -1;
	m_manimRunningLeft = -1;

	m_pSpriteLarge = 0;

	switchToBody(BODY_LARGE);
	setHasCameraTrigger(false);
}

void Player::startLife()
{
	LOGI("Player startLife()");
	m_ax = 0;
	m_ay = 0;

	m_vx = 0;
	m_vy = 0;

	m_px = m_originalX;
	m_py = m_originalY;

	m_prevpx = m_px;
	m_prevpy = m_py;

	m_isFacingRight = m_originalFacingRight;
	m_isFast = false;
	m_isAllowJumping = true;

	m_state = STATE_IDLE;

	m_isLive = true;
}

void Player::setBrother(Brother brother)
{
	if (m_pSpriteLarge)
	{
		if (m_manimRunningRight > -1) m_pSpriteLarge->unmapMAnim(m_manimRunningRight);
		if (m_manimRunningLeft > -1) m_pSpriteLarge->unmapMAnim(m_manimRunningLeft);
	}

	m_brother = brother;
	switch (brother)
	{
	case MARIO:
		m_pSpriteLarge = SpriteManager::getInstance()->getRes(SPRITE_mario_large_myspr);
		m_pSpriteLarge->setImage(ImageManager::getInstance()->getRes(IMAGE_mario_large_tga));
		break;
	case LUGI:
		//m_pSpriteLarge = SpriteManager::getInstance()->getRes(SPRITE_mario_large_myspr);
		//m_pSpriteLarge->setImage(ImageManager::getInstance()->getRes(IMAGE_mario_large_tga));
		break;
	}

	m_manimRunningRight = m_pSpriteLarge->mapAnim(ANIM_RUNNING_RIGHT);
	m_manimRunningLeft = m_pSpriteLarge->mapAnim(ANIM_RUNNING_LEFT);
}

Player::~Player()
{
	if (m_pSpriteLarge)
	{
		if (m_manimRunningRight > -1) m_pSpriteLarge->unmapMAnim(m_manimRunningRight);
		if (m_manimRunningLeft > -1) m_pSpriteLarge->unmapMAnim(m_manimRunningLeft);
	}
}

void Player::render(gata::graphic::Graphic* g)
{
	int xRender = m_px - m_pWorld->currentCameraX();
	int yRender = m_py - m_pWorld->currentCameraY();
	switch (m_state)
	{
	case STATE_IDLE:
		m_pSpriteLarge->paintFrame(g, m_isFacingRight ? FRAME_NORMAL_RIGHT : FRAME_NORMAL_LEFT, xRender, yRender);
		break;
	case STATE_STARTING_RUN: case STATE_RUNNING: case STATE_STOPPING_RUN:
		m_pSpriteLarge->paintAndUpdateMAnim(g, m_isFacingRight ? m_manimRunningRight : m_manimRunningLeft, xRender, yRender);
		break;
	case STATE_TURNING_BACK:
		m_pSpriteLarge->paintFrame(g, m_isFacingRight ? FRAME_TURNING_RIGHT : FRAME_TURNING_LEFT, xRender, yRender);
		break;
	case STATE_DUCKING:
		m_pSpriteLarge->paintFrame(g, m_isFacingRight ? FRAME_DUCKING_RIGHT : FRAME_DUCKING_LEFT, xRender, yRender);
		break;
	case STATE_JUMPING:
		m_pSpriteLarge->paintFrame(g, m_isFacingRight ? FRAME_JUMPING_RIGHT : FRAME_JUMPING_LEFT, xRender, yRender);
		break;
	}
}

void Player::update()
{
	if (m_state != STATE_JUMPING && checkFalling())
	{
		switchToState_Jumping(0);
	}

	switch (m_state)
	{
	case STATE_IDLE:
		updateState_Idle();
		break;

	case STATE_STARTING_RUN:
		updateState_StartingRun();
		break;

	case STATE_RUNNING:
		updateState_Running();
		break;

	case STATE_STOPPING_RUN:
		updateState_StoppingRun();
		break;

	case STATE_TURNING_BACK:
		updateState_TurningBack();
		break;

	case STATE_DUCKING:
		updateState_Ducking();
		break;

	case STATE_JUMPING:
		updateState_Jumping();
		break;
	}
	
	Entity::updateMovement();

	if ( m_px < 0 || m_px + m_width > m_pWorld->map().totalWidth() )
	{
		
		if (m_px < 0) m_px = 0;
		else m_px = m_pWorld->map().totalWidth() - m_width;
	}

	if (!m_pWorld->isJumpHolding() && m_state != STATE_JUMPING)
	{
		m_isAllowJumping = true;
	}
}

void Player::switchToBody(BodyStyle body)
{
	m_bodyStyle = body;
	switch (body)
	{
	case BODY_SMALL:
		m_width = BODY_SMALL_WIDTH;
		m_height = BODY_SMALL_HEIGHT;
		break;
	case BODY_LARGE:
		m_width = BODY_LARGE_WIDTH;
		m_height = BODY_LARGE_HEIGHT;
		break;
	case BODY_FLOWER:
		m_width = BODY_FLOWER_WIDTH;
		m_height = BODY_FLOWER_HEIGHT;
		break;
	}
}

void Player::solveCollision()
{
	for (int i = 0; i < m_collObjsNumber; i++) if (m_pCollInfosList[i].m_isCollision)
	{
		const GameObject* other = m_ppCollObjsList[i];
		CollisionInfo& info = m_pCollInfosList[i];
		int kind = other->getKind();

		switch (kind)
		{
			case KIND_TILE_NORMAL_BRICK:
			case KIND_TILE_QUESTION_BRICK:
			case KIND_TILE_BACKGROUND:
			{
				switch (info.m_edgeResult)
				{
					case EDGE_TOP:
					{
						assert(m_state == STATE_JUMPING);

						const int k_epsilon = other->width() / 4;

						if (info.m_length <= k_epsilon)
						{
							assert(m_px != other->px());
							const TiledObject* pTile = (const TiledObject*)other;
							if ( m_px > other->px() )
							{
								if ( pTile->colIndex() + 1 < m_pWorld->map().colsNumber() )
								{
									if ( ! m_pWorld->getTiledObject(pTile->colIndex() + 1, pTile->rowIndex())->canCollideWith(this) )
									{
										m_px = other->px() + other->width();
										//printf("Linh hoat cho nhay sang PHAI ti\n");
										break;
									}
								}
							}
							else
							{
								if ( pTile->colIndex() - 1 >= 0 )
								{
									if ( ! m_pWorld->getTiledObject(pTile->colIndex() - 1, pTile->rowIndex())->canCollideWith(this) )
									{
										m_px = other->px() - m_width;
										//printf("Linh hoat cho nhay sang TRAI ti\n");
										break;
									}
								}
							}
						}

						m_py = other->py() + other->height();
						m_vy = 0;
						break;
					}

					case EDGE_BOTTOM:
					{
						m_py = other->py() - m_height;
						landing();
						break;
					}

					case EDGE_LEFT:
					{
						m_px = other->px() + other->width();
						break;
					}

					case EDGE_RIGHT:
					{
						m_px = other->px() - m_width;
						break;
					}
				} // switch (info.m_edgeResult)

				break;
			} // case

			case KIND_ENEMY_GOOMBA:
			case KIND_ENEMY_KOOPA:
			{
				m_px = info.m_newX;
				m_py = info.m_newY;
				if (info.m_edgeResult == EDGE_BOTTOM)
				{
					
					switchToState_Jumping(JUMP_VY * 3 / 4);
				}
				else
				{
					if (kind == KIND_ENEMY_KOOPA)
					{
						Koopa* pKoopa = (Koopa*)other;
						if (pKoopa->isIdle())
						{
							break;
						}
					}
					// todo
					// die
					m_px = m_py = 0;
				}
				break;
			} // case KIND_ENEMY_GOOMBA:

			case KIND_OTHER_MUSHROOM:
			{
				break;
			}

		} // switch (other->getKind())

	} // for

	// Always remember to call this
	clearCollisionList();
}

bool Player::canCollideWith(const GameObject* other) const
{
	switch ( other->getKind() )
	{
		case KIND_TILE_NORMAL_BRICK:
		case KIND_TILE_QUESTION_BRICK:
		case KIND_TILE_BACKGROUND:
		case KIND_ENEMY_GOOMBA:
		case KIND_ENEMY_KOOPA:
		case KIND_OTHER_MUSHROOM:
			return true;
	}
	return false;
}

bool Player::isValidRightKeyDown()
{
	return m_pWorld->isRightHolding() && !m_pWorld->isLeftHolding();
}

bool Player::isValidLeftKeyDown()
{
	return !m_pWorld->isRightHolding() && m_pWorld->isLeftHolding();
}

bool Player::isSameDirKeyDown()
{
	if (m_isFacingRight) return m_pWorld->isRightHolding();
	else return m_pWorld->isLeftHolding();
}

bool Player::isOppDirKeyDown()
{
	if (m_isFacingRight) return m_pWorld->isLeftHolding();
	else return m_pWorld->isRightHolding();
}

void Player::landing()
{
	m_ay = m_vy = 0;
	if (isValidRightKeyDown())
	{
		m_ax = 0;
		m_vx = MAX_VX;
		m_isFacingRight = true;
		m_state = STATE_RUNNING;
	}
	else if (isValidLeftKeyDown())
	{
		m_ax = 0;
		m_vx = -MAX_VX;
		m_isFacingRight = false;
		m_state = STATE_RUNNING;
	}
	else
	{
		m_ax = m_vx = 0;
		m_state = STATE_IDLE;
	}
}

void Player::switchToState_Jumping(int vy)
{
	m_isAllowJumping = false;
	m_ax = m_vx = 0;
	m_ay = GRAVITY;
	m_vy = vy;
	m_state = STATE_JUMPING;
}

void Player::updateState_Idle()
{
	assert(m_ax == 0);
	assert(m_vx == 0);
	if (isValidRightKeyDown())
	{
		m_ax = AX;
		m_isFacingRight = true;

		m_pSpriteLarge->resetMAnim(m_manimRunningRight);
		m_state = STATE_STARTING_RUN;
		return;
	}
	else if (isValidLeftKeyDown())
	{
		m_ax = -AX;
		m_isFacingRight = false;

		m_pSpriteLarge->resetMAnim(m_manimRunningLeft);
		m_state = STATE_STARTING_RUN;
		return;
	}
	if (g_pGame->isKeyDown(KEY_DOWN))
	{
		m_state = STATE_DUCKING;
		return;
	}
	if (m_pWorld->isJumpHolding() && m_isAllowJumping)
	{
		switchToState_Jumping(m_pWorld->isFastHolding() ? JUMP_VY_FAST : JUMP_VY);
		return;
	}
}

void Player::updateState_StartingRun()
{
	assert(m_ax != 0);
	if (m_isFacingRight)
	{
		if (m_vx >= MAX_VX)
		{
			m_ax = 0;
			m_vx = MAX_VX;
			m_state = STATE_RUNNING;
			return;
		}
	}
	else
	{
		if (m_vx <= -MAX_VX)
		{
			m_ax = 0;
			m_vx = -MAX_VX;
			m_state = STATE_RUNNING;
			return;
		}
	}

	if (!isSameDirKeyDown())
	{
		m_ax = -m_ax;
		m_state = STATE_STOPPING_RUN;
		return;
	}

	if (g_pGame->isKeyDown(KEY_DOWN))
	{
		m_ax = -m_ax;
		m_state = STATE_DUCKING;
		return;
	}

	if (m_pWorld->isJumpHolding() && m_isAllowJumping)
	{
		switchToState_Jumping(m_pWorld->isFastHolding() ? JUMP_VY_FAST : JUMP_VY);
		return;
	}
}

void Player::updateState_Running()
{
	assert(m_ax == 0);
	if (!isSameDirKeyDown())
	{
		m_ax = m_isFacingRight ? -AX : AX;
		m_state = STATE_STOPPING_RUN;
		return;
	}

	if (g_pGame->isKeyDown(KEY_DOWN))
	{
		m_ax = m_isFacingRight ? -AX : AX;
		m_state = STATE_DUCKING;
		return;
	}

	if (m_pWorld->isJumpHolding() && m_isAllowJumping)
	{
		switchToState_Jumping(m_pWorld->isFastHolding() ? JUMP_VY_FAST : JUMP_VY);
		return;
	}

	if (m_pWorld->isFastHolding())
	{
		m_vx = m_isFacingRight ? MAX_VX_FAST : -MAX_VX_FAST;
	}
	else
	{
		m_vx = m_isFacingRight ? MAX_VX : -MAX_VX;
	}
}

void Player::updateState_StoppingRun()
{
	assert(m_ax != 0);
	if ((m_isFacingRight && m_vx <= 0) || (!m_isFacingRight && m_vx >= 0))
	{
		m_ax = 0;
		m_vx = 0;
		m_state = STATE_IDLE;
		return;
	}

	if (isOppDirKeyDown())
	{
		m_isFacingRight = !m_isFacingRight;
		m_state = STATE_TURNING_BACK;
		return;
	}

	if (isSameDirKeyDown())
	{
		m_ax = -m_ax;
		m_state = STATE_STARTING_RUN;
		return;
	}

	if (g_pGame->isKeyDown(KEY_DOWN))
	{
		m_state = STATE_DUCKING;
		return;
	}

	if (m_pWorld->isJumpHolding() && m_isAllowJumping)
	{
		switchToState_Jumping(m_pWorld->isFastHolding() ? JUMP_VY_FAST : JUMP_VY);
		return;
	}
}

void Player::updateState_TurningBack()
{
	assert(m_ax != 0);
	if ((m_isFacingRight && m_vx >= 0) || (!m_isFacingRight && m_vx <= 0))
	{
		if (isSameDirKeyDown())
		{
			m_ax = m_isFacingRight ? AX : -AX;
			m_vx = 0;
			m_state = STATE_STARTING_RUN;
			return;
		}

		if (isOppDirKeyDown())
		{
			m_isFacingRight = !m_isFacingRight;
			m_ax = m_isFacingRight ? AX : -AX;
			m_vx = 0;
			m_state = STATE_STARTING_RUN;
			return;
		}

		m_ax = 0;
		m_vx = 0;
		m_state = STATE_IDLE;
		return;
	}

	if (g_pGame->isKeyDown(KEY_DOWN))
	{
		m_state = STATE_DUCKING;
		return;
	}

	if (m_pWorld->isJumpHolding() && m_isAllowJumping)
	{
		switchToState_Jumping(m_pWorld->isFastHolding() ? JUMP_VY_FAST : JUMP_VY);
		return;
	}
}

void Player::updateState_Ducking()
{
	if ((m_isFacingRight && m_vx <= 0) || (!m_isFacingRight && m_vx >= 0))
	{
		m_ax = m_vx = 0;
		if (!g_pGame->isKeyDown(KEY_DOWN))
		{
			m_state = STATE_IDLE;
			return;
		}
	}
}

void Player::updateState_Jumping()
{
	if ( m_py + m_height >= m_pWorld->map().totalHeight() )
	{
		m_py = m_pWorld->map().totalHeight() - m_height;
		landing();
		return;
	}

	if (isValidRightKeyDown())
	{
		m_vx = m_pWorld->isFastHolding() ? MAX_VX_FAST : MAX_VX;
	}
	else if (isValidLeftKeyDown())
	{
		m_vx = m_pWorld->isFastHolding() ? -MAX_VX_FAST : -MAX_VX;
	}
	else
	{
		m_vx = 0;
	}
}
