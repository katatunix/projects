#ifndef _WORLD_H_
#define _WORLD_H_

#include <gata/graphic/Graphic.h>

#include "Tile/tile_define.h"
#include "Tile/Map.h"

class TiledObject;
class Player;

class World
{
public:
	World();
	virtual ~World();

	void load(const char* szFilePath);
	void unload();

	void setCamera(int x, int y, int width, int height);
	void setCameraPosition(int x, int y);
	void setCameraSize(int width, int height);

	void setCurrentCameraX(int x) { m_currentCameraX = x; }
	void setCurrentCameraY(int y) { m_currentCameraY = y; }

	int currentCameraX() const { return m_currentCameraX; }
	int currentCameraY() const { return m_currentCameraY; }
	int cameraWidth() const { return m_cameraWidth; }
	int cameraHeight() const { return m_cameraHeight; }

	Map& map() { return m_map; }

	inline TiledObject* getTiledObject(int c, int r) const
	{
		const char& layerIndex = m_ppMark[c][r];
		return layerIndex >= 0 ? m_pTiledObjectsTable[layerIndex][c][r] : 0;
	}

	void update();
	void render(gata::graphic::Graphic* g);

	void decLayerMark(int i, int j);

	//
	bool isLeftHolding() { return m_isLeftHolding; }
	bool isRightHolding() { return m_isRightHolding; }
	bool isJumpHolding() { return m_isJumpHolding; }
	bool isFastHolding() { return m_isFastHolding; }

	void setLeftHolding(bool b) { m_isLeftHolding = b; }
	void setRightHolding(bool b) { m_isRightHolding = b; }
	void setJumpHolding(bool b) { m_isJumpHolding = b; }
	void setFastHolding(bool b) { m_isFastHolding = b; }

	//
	void getPlayerPos(int& x, int& y);
	int getLiveEntitiesCount();

private:
	TiledObject* createTiledObject(int data, int layer, int col, int row);
	TiledObject* createBackground(int data, int layer, int col, int row);

	bool checkAndSolveCollision();
	void reduceCollisionList();

	//
	Map m_map;

	TiledObject* m_pTiledObjectsTable[MAX_MAP_LAYERS_NUMBER][MAX_MAP_COLS_NUMBER][MAX_MAP_ROWS_NUMBER];
	char m_ppMark[MAX_MAP_COLS_NUMBER][MAX_MAP_ROWS_NUMBER];

	Player* m_pPlayer;

	//
	int m_currentCameraX, m_currentCameraY;
	int m_cameraWidth, m_cameraHeight;

	//
	bool m_isLeftHolding;
	bool m_isRightHolding;
	bool m_isJumpHolding;
	bool m_isFastHolding;
};

#endif
