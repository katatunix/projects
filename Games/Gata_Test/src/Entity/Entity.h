#ifndef _ENTITY_H_
#define _ENTITY_H_

#include "../GameObject.h"

#include <gata/core/Autolist.h>

class World;

class Entity : public GameObject, public gata::core::Autolist<Entity>
{
public:
	Entity() :			GameObject(),
						m_canStartLife(true),
						m_hasCameraTrigger(false),
						m_hasOriginalData(false)
	{
	}

	Entity(int kind) :	GameObject(kind),
						m_canStartLife(true),
						m_hasCameraTrigger(false),
						m_hasOriginalData(false)
	{
	}

	inline int originalX() const { return m_originalX; }
	inline int originalY() const { return m_originalY; }
	void setOriginal(int x, int y) { m_originalX = x; m_originalY = y; }

	inline int vx() const { return m_vx; }
	inline int vy() const { return m_vy; }
	void setvx(int vx) { m_vx = vx; }
	void setvy(int vy) { m_vy = vy; }

	void setWorld(World* pWorld) { m_pWorld = pWorld; }

	bool checkFalling();
	void updateMovement();

	inline bool canStartLife() { return m_canStartLife; }
	void setCanStartLife(bool c) { m_canStartLife = c; }

	inline bool hasCameraTrigger() const { return m_hasCameraTrigger; }
	void setHasCameraTrigger(bool b) { m_hasCameraTrigger = b; }
	
	inline bool hasOriginalData() const { return m_hasOriginalData; }
	void setHasOriginalData(bool b) { m_hasOriginalData = b; }

	//
	virtual void startLife() = 0;

protected:
	int m_originalX;
	int m_originalY;

	int m_ax, m_ay;
	int m_vx, m_vy;

	bool m_canStartLife;
	bool m_hasCameraTrigger;

	bool m_hasOriginalData;

	World* m_pWorld;
};

#endif
