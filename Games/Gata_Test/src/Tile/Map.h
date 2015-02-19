#ifndef _MAP_H_
#define _MAP_H_

#include "tile_define.h"
#include <gata/3rdparty/jsoncpp/json.h>
#include <gata/core/macro.h>

using namespace Json;

enum TLayerType
{
	ETileLayer,
	EObjectLayer,
	ENoneLayer
};

//========================================================================================================
class Layer
{
public:
	Layer() : m_type(ENoneLayer) { }
	Layer(TLayerType type) : m_type(type) { }

	virtual ~Layer() { }

	TLayerType type() const { return m_type; }

	const char* name() const { return m_szName; }
	void setName(const char* szName);

	int firstColIndex() { return m_firstColIndex; }
	void setFirstColIndex(int v) { m_firstColIndex = v; }

	int firstRowIndex() { return m_firstRowIndex; }
	void setFirstRowIndex(int v) { m_firstRowIndex = v; }

	int colsNumber() { return m_colsNumber; }
	void setColsNumber(int v) { m_colsNumber = v; }

	int rowsNumber() { return m_rowsNumber; }
	void setRowsNumber(int v) { m_rowsNumber = v; }

protected:
	TLayerType m_type;
	char m_szName[MAX_MAP_STRING_LENGTH + 1];

	int m_firstColIndex, m_firstRowIndex;
	int m_colsNumber, m_rowsNumber;
};

//========================================================================================================
class TileLayer : public Layer
{
public:
	TileLayer() : Layer(ETileLayer) { }

	int getData(int col, int row) { return m_data[col][row]; }
	void setData(int col, int row, int v) { m_data[col][row] = v; }

private:
	int m_data[MAX_MAP_COLS_NUMBER][MAX_MAP_ROWS_NUMBER];
};

//========================================================================================================
typedef struct _ObjectMapData
{
	int x, y, width, height;
	char szName[MAX_MAP_STRING_LENGTH + 1];
	char szType[MAX_MAP_STRING_LENGTH + 1];
	Value properties;
} ObjectMapData;

//========================================================================================================
class ObjectLayer : public Layer
{
public:
	ObjectLayer() : Layer(EObjectLayer), m_objectsNumber(0), m_pObjectsList(0) { }

	~ObjectLayer()
	{
		SAFE_DEL_ARRAY(m_pObjectsList);
	}

	int objectsNumber() { return m_objectsNumber; }

	void setObjectsNumber(int n)
	{
		assert(n > 0);
		m_objectsNumber = n;
		SAFE_DEL_ARRAY(m_pObjectsList);
		m_pObjectsList = new ObjectMapData[n];
	}

	void setObjectData(int index, const ObjectMapData& objData)
	{
		assert(index >= 0 && index < m_objectsNumber);
		assert(m_pObjectsList);
		m_pObjectsList[index] = objData;
	}

	const ObjectMapData& getObjectData(int index) const
	{
		assert(index >= 0 && index < m_objectsNumber);
		assert(m_pObjectsList);
		return m_pObjectsList[index];
	}

private:
	ObjectMapData* m_pObjectsList;
	int m_objectsNumber;
};

//========================================================================================================
class Map
{
public:
	Map();
	virtual ~Map();

	void load(const char* szFilePath);
	void unload();

	int colsNumber() { return m_colsNumber; }
	int rowsNumber() { return m_rowsNumber; }

	int tileWidth() { return m_tileWidth; }
	int tileHeight() { return m_tileHeight; }

	int totalWidth() { return m_colsNumber * m_tileWidth; }
	int totalHeight() { return m_rowsNumber * m_tileHeight; }

	Layer* getLayer(int index) { return m_ppLayersList[index]; }
	Layer* getLayer(const char* szName);
	int layersNumber() { return m_layersNumber; }

private:
	int m_colsNumber, m_rowsNumber;
	int m_tileWidth, m_tileHeight;

	Layer* m_ppLayersList[MAX_MAP_LAYERS_NUMBER];
	int m_layersNumber;
};

#endif
