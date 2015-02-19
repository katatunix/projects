#ifndef _PLAYER_H_
#define _PLAYER_H_

#include "Entity.h"
#include <gata/sprite/Sprite.h>

class Player : public Entity
{
public:
	enum BodyStyle
	{
		BODY_SMALL,
		BODY_LARGE,
		BODY_FLOWER
	};

	enum Brother
	{
		MARIO,
		LUGI
	};

	enum State
	{
		STATE_IDLE,
		STATE_STARTING_RUN,
		STATE_RUNNING,
		STATE_STOPPING_RUN,
		STATE_TURNING_BACK,
		STATE_DUCKING,
		STATE_JUMPING,
	};

	//
	Player();
	virtual ~Player();

	//
	bool originalFacingRight() const { return m_originalFacingRight; }
	void setOrinigalFacingRight(bool b) { m_originalFacingRight = b; }

	void setBrother(Brother brother);
	Brother brother() const { return m_brother; }

	State getState() { return m_state; }
	BodyStyle bodyStyle() { return m_bodyStyle; }

	//
	void render(gata::graphic::Graphic* g);
	void update();

	bool canCollideWith(const GameObject* other) const;
	void solveCollision();

	void startLife();

	//
	void switchToState_Jumping(int vy);

private:
	void switchToBody(BodyStyle body);
	
	bool isValidRightKeyDown();
	bool isValidLeftKeyDown();

	bool isSameDirKeyDown();
	bool isOppDirKeyDown();

	void landing();

	//
	void updateState_Idle();
	void updateState_StartingRun();
	void updateState_Running();
	void updateState_StoppingRun();
	void updateState_TurningBack();
	void updateState_Ducking();
	void updateState_Jumping();

private:
	bool m_originalFacingRight;

	bool m_isFacingRight;
	bool m_isFast;
	bool m_isAllowJumping;

	State m_state;
	Brother m_brother;
	BodyStyle m_bodyStyle;

	//
	gata::sprite::Sprite* m_pSpriteLarge;
	int m_manimRunningRight;
	int m_manimRunningLeft;
};

#endif
