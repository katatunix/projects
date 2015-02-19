#include "Map.h"

#include <gata/utils/MyUtils.h>

#include <stdio.h>
#include <string>

using namespace gata::utils;

//==================================================================================================================
// Layer

void Layer::setName(const char* szName)
{
	assert(strlen(szName) <= MAX_MAP_STRING_LENGTH);
	strcpy(m_szName, szName);
}

Layer* Map::getLayer(const char* szName)
{
	for (int i = 0; i < m_layersNumber; i++)
	{
		if ( ! strcmp( m_ppLayersList[i]->name(), szName ) )
		{
			return m_ppLayersList[i];
		}
	}
	assert(0);
	return 0;
}

//==================================================================================================================
// Map

Map::Map() : m_colsNumber(0), m_rowsNumber(0), m_tileWidth(0), m_tileHeight(0)
{
	for (int i = 0; i < MAX_MAP_LAYERS_NUMBER; i++)
	{
		m_ppLayersList[i] = 0;
	}
}

Map::~Map()
{
	unload();
}

void Map::load(const char* szFilePath)
{
	int length;
	FILE* f = my_fopen(szFilePath, &length);
	assert(f);
	fseek(f, 0, SEEK_END);
	int count = ftell(f);
	assert(count > 0);
	fseek(f, 0, SEEK_SET);
	char* buffer = new char[count];
	memset(buffer, 0, count);
	fread(buffer, 1, count, f);
	fclose(f);

	Reader reader;
	Value oMap;
	bool success = reader.parse(buffer, oMap);
	assert(success);

	//-----------------------------------------------------------

	m_colsNumber = oMap["width"].asInt();
	m_rowsNumber = oMap["height"].asInt();

	m_tileWidth = oMap["tilewidth"].asInt();
	m_tileHeight = oMap["tileheight"].asInt();

	m_layersNumber = oMap["layers"].size();
	assert(m_layersNumber <= MAX_MAP_LAYERS_NUMBER);

	for (int i = 0; i < m_layersNumber; i++)
	{
		Value oLayer = oMap["layers"][i];
		Layer* layer = 0;

		if (oLayer["type"].asString().compare("tilelayer") == 0)
		{

			TileLayer* p = new TileLayer();
			p->setColsNumber( oLayer["width"].asInt() );
			p->setRowsNumber( oLayer["height"].asInt() );
			p->setFirstColIndex( oLayer["x"].asInt() );
			p->setFirstRowIndex( oLayer["y"].asInt() );

			p->setName( oLayer["name"].asString().c_str() );

			Value oData = oLayer["data"];

			assert(oData.size() == p->colsNumber() * p->rowsNumber());
			int k = 0;
			for (int row = 0; row < p->rowsNumber(); row++) for (int col = 0; col < p->colsNumber(); col++)
			{
				p->setData( col, row, oData[k].asInt() );
				k++;
			}

			layer = p;
		}
		else
		{
			ObjectLayer* p = new ObjectLayer();
			p->setColsNumber( oLayer["width"].asInt() );
			p->setRowsNumber( oLayer["height"].asInt() );
			p->setFirstColIndex( oLayer["x"].asInt() );
			p->setFirstRowIndex( oLayer["y"].asInt() );

			p->setName( oLayer["name"].asString().c_str() );

			Value oObjects = oLayer["objects"];

			int objNumber = oObjects.size();
			if (objNumber > 0)
			{
				p->setObjectsNumber(objNumber);
				for (int i = 0; i < objNumber; i++)
				{
					ObjectMapData objData;
					objData.x = oObjects[i]["x"].asInt();
					objData.y = oObjects[i]["y"].asInt();
					objData.width = oObjects[i]["width"].asInt();
					objData.height = oObjects[i]["height"].asInt();
					strcpy(objData.szName, oObjects[i]["name"].asString().c_str());
					strcpy(objData.szType, oObjects[i]["type"].asString().c_str());
					objData.properties = oObjects[i]["properties"];

					p->setObjectData(i, objData);
				}
			}

			layer = p;
		}

		m_ppLayersList[i] = layer;
	}

	SAFE_DEL_ARRAY(buffer);
}

void Map::unload()
{
	m_colsNumber = 0;
	m_rowsNumber = 0;
	m_tileWidth = 0;
	m_tileHeight = 0;
	for (int i = 0; i < MAX_MAP_LAYERS_NUMBER; i++)
	{
		SAFE_DEL(m_ppLayersList[i]);
	}
}
