#ifndef _GAME_OBJECT_POOL_H_
#define _GAME_OBJECT_POOL_H_

#include <cassert>
#include <gata/core/Singleton.h>
#include <gata/core/macro.h>

template<typename T>
class GameObjectPool : public gata::core::Singleton< GameObjectPool<T> >
{
public:
	GameObjectPool(int capacity)
	{
		assert(capacity > 0);
		m_capacity = capacity;
		m_pObjectsList = new T[capacity];
	}

	~GameObjectPool()
	{
		SAFE_DEL_ARRAY(m_pObjectsList);
	}

	T* getFreeObj()
	{
		for (int i = 0; i < m_capacity; i++)
		{
			if ( ! m_pObjectsList[i].isLive() )
			{
				return &m_pObjectsList[i];
			}
		}
		assert(0);
		return 0;
	}
	
	T* getWildObj()
	{
		for (int i = 0; i < m_capacity; i++)
		{
			if ( ! m_pObjectsList[i].isLive() && ! m_pObjectsList[i].hasOriginalData() )
			{
				return &m_pObjectsList[i];
			}
		}
		assert(0);
		return 0;
	}

	int capacity() { return m_capacity; }

private:
	T*		m_pObjectsList;
	int		m_capacity;
};

#endif
