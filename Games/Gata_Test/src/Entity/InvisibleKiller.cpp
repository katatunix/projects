#include "InvisibleKiller.h"
#include <gata/core/macro.h>

InvisibleKiller::InvisibleKiller() : Entity(KIND_OTHER_INVISIBLE_KILLER)
{

}

InvisibleKiller::~InvisibleKiller()
{
	
}

void InvisibleKiller::render(gata::graphic::Graphic* g)
{
	
}

void InvisibleKiller::update()
{
	if (m_risingStartY - m_py >= m_maxDY)
	{
		setLive(false);
		return;
	}

	Entity::updateMovement();
}

void InvisibleKiller::solveCollision()
{
	for (int i = 0; i < m_collObjsNumber; i++) if (m_pCollInfosList[i].m_isCollision)
	{
		const GameObject* other = m_ppCollObjsList[i];
		CollisionInfo& info = m_pCollInfosList[i];
		int kind = other->getKind();

		//LOGI("[InvisibleKiller::solveCollision()] m_px=%d, info.m_newX=%d", m_px, info.m_newX);
		m_px = info.m_newX;
		m_py = info.m_newY;
	}

	clearCollisionList();
}

bool InvisibleKiller::canCollideWith(const GameObject* other) const
{
	if ( other->isEnemy() || other->isKindOf(KIND_OTHER_MUSHROOM) ) return true;
	return false;
}

void InvisibleKiller::startLife()
{
	m_ax = m_ay = 0;
	m_vx = 0;
	m_vy = -1;
	m_prevpx = m_px;
	m_prevpy = m_py;

	m_risingStartY = m_py;
	setLive(true);
}
