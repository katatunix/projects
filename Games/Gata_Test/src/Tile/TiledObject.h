#ifndef _TILE_OBJECT_H_
#define _TILE_OBJECT_H_

#include "../GameObject.h"

class World;

class TiledObject : public GameObject
{
public:
	TiledObject(int layer, int col, int row, World* pWorld) :
			GameObject(),
			m_layerIndex(layer),
			m_colIndex(col),
			m_rowIndex(row),
			m_pWorld(pWorld) { }

	inline int layerIndex() const { return m_layerIndex; }
	void setLayerIndex(int t) { m_layerIndex = t; }
	void incLayerIndex() { m_layerIndex++; }
	void decLayerIndex() { m_layerIndex--; }

	inline int colIndex() const { return m_colIndex; }
	inline int rowIndex() const { return m_rowIndex; }

protected:
	int m_layerIndex;
	int m_colIndex, m_rowIndex;
	World* m_pWorld;
};

#endif
