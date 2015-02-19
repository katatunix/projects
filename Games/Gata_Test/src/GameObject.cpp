#include "GameObject.h"

#include <gata/utils/MyUtils.h>

#include <gata/core/macro.h>
#include <math.h>

#include "Tile/TiledObject.h"

using namespace gata::utils;

bool GameObject::checkCollision(const GameObject* srcObj, const GameObject* dstObj,
			int& edgeResult_dst, int& distSqr_src, int& distSqr_dst, int& offset_dst, int& length,
			int& x_src_new, int& y_src_new, int& x_dst_new, int& y_dst_new)
{
	assert(srcObj && dstObj);
	assert(srcObj->isLive() && dstObj->isLive());
	assert(srcObj->canCollideWith(dstObj) && dstObj->canCollideWith(srcObj));

	int width_src = srcObj->width();
	int height_src = srcObj->height();
	int width_dst = dstObj->width();
	int height_dst = dstObj->height();
	
	if (!isRectCollision(
		srcObj->px(), srcObj->py(), width_src, height_src,
		dstObj->px(), dstObj->py(), width_dst, height_dst
	)) return false;

	int x_src = srcObj->prevpx();
	int y_src = srcObj->prevpy();
	int x_dst = dstObj->prevpx();
	int y_dst = dstObj->prevpy();
	int vx_src = srcObj->px() - x_src;
	int vy_src = srcObj->py() - y_src;
	int vx_dst = dstObj->px() - x_dst;
	int vy_dst = dstObj->py() - y_dst;

	//--------------------------------------------------------------------------------------
	assert(!isRectCollision(x_src, y_src, width_src, height_src, x_dst, y_dst, width_dst, height_dst));
	assert(vx_src != 0 || vy_src != 0 || vx_dst != 0 || vy_dst != 0);
	assert(vx_src != vx_dst || vy_src != vy_dst);

	int xx_src = x_src + width_src;
	int yy_src = y_src + height_src;
	int xx_dst = x_dst + width_dst;
	int yy_dst = y_dst + height_dst;

	//
	float t[4];

	t[0] = mydiv(xx_src - x_dst, vx_dst - vx_src);
	if (t[0] >= 0.0f)
	{
		float y_src_new_tmp = y_src + vy_src * t[0];
		float y_dst_new_tmp = y_dst + vy_dst * t[0];
		if (y_src_new_tmp + height_src < y_dst_new_tmp || y_dst_new_tmp + height_dst < y_src_new_tmp) t[0] = -1.0f;
	}
	t[1] = mydiv(x_src - xx_dst, vx_dst - vx_src);
	if (t[1] >= 0.0f)
	{
		float y_src_new_tmp = y_src + vy_src * t[1];
		float y_dst_new_tmp = y_dst + vy_dst * t[1];
		if (y_src_new_tmp + height_src < y_dst_new_tmp || y_dst_new_tmp + height_dst < y_src_new_tmp) t[1] = -1.0f;
	}
	t[2] = mydiv(yy_src - y_dst, vy_dst - vy_src);
	if (t[2] >= 0.0f)
	{
		float x_src_new_tmp = x_src + vx_src * t[2];
		float x_dst_new_tmp = x_dst + vx_dst * t[2];
		if (x_src_new_tmp + width_src < x_dst_new_tmp || x_dst_new_tmp + width_dst < x_src_new_tmp) t[2] = -1.0f;
	}
	t[3] = mydiv(y_src - yy_dst, vy_dst - vy_src);
	if (t[3] >= 0.0f)
	{
		float x_src_new_tmp = x_src + vx_src * t[3];
		float x_dst_new_tmp = x_dst + vx_dst * t[3];
		if (x_src_new_tmp + width_src < x_dst_new_tmp || x_dst_new_tmp + width_dst < x_src_new_tmp) t[3] = -1.0f;
	}

	//
	int k = -1;
	float mint = MY_MAX_FLOAT;
	for (int i = 0; i < 4; i++) if (t[i] >= 0.0f && t[i] < mint) { mint = t[i]; k = i; }
	assert(k > -1);
	assert(mint < MY_MAX_FLOAT);

	float x_src_new_tmp = x_src + vx_src * mint;
	float y_src_new_tmp = y_src + vy_src * mint;
	float x_dst_new_tmp = x_dst + vx_dst * mint;
	float y_dst_new_tmp = y_dst + vy_dst * mint;

	int x_src_res[4] =
	{
		(int)floorf(x_src_new_tmp),
		(int)floorf(x_src_new_tmp),
		(int)ceilf(x_src_new_tmp),
		(int)ceilf(x_src_new_tmp)
	};
	int y_src_res[4] =
	{
		(int)floorf(y_src_new_tmp),
		(int)ceilf(y_src_new_tmp),
		(int)floorf(y_src_new_tmp),
		(int)ceilf(y_src_new_tmp)
	};
	int x_dst_res[4] =
	{
		(int)floorf(x_dst_new_tmp),
		(int)floorf(x_dst_new_tmp),
		(int)ceilf(x_dst_new_tmp),
		(int)ceilf(x_dst_new_tmp)
	};
	int y_dst_res[4] =
	{
		(int)floorf(y_dst_new_tmp),
		(int)ceilf(y_dst_new_tmp),
		(int)floorf(y_dst_new_tmp),
		(int)ceilf(y_dst_new_tmp)
	};

	if (k <= 1)
	{
		edgeResult_dst = k == 0 ? EDGE_LEFT : EDGE_RIGHT;
		bool found = false;

		for (int i = 0; i < 4; i++)
		{
			if (found) break;
			for (int j = 0; j < 4; j++)
			{
				x_src_new = x_src_res[i];
				y_src_new = y_src_res[i];
				x_dst_new = x_dst_res[j];
				y_dst_new = y_dst_res[j];
				if ( !isRectCollision(x_src_new, y_src_new, width_src, height_src, x_dst_new, y_dst_new, width_dst, height_dst) )
				{
					length = getCommonLength(y_src_new, height_src, y_dst_new, height_dst);
					if (length >= 0)
					{
						offset_dst = y_src_new - y_dst_new;
						
						distSqr_src = (int)( MY_SQR(x_src_new - x_src) + MY_SQR(y_src_new - y_src) );
						distSqr_dst = (int)( MY_SQR(x_dst_new - x_dst) + MY_SQR(y_dst_new - y_dst) );

						found = true;
						break;
					}
				}
			}
		}

		assert(found);
	}
	else
	{
		edgeResult_dst = k == 2 ? EDGE_TOP : EDGE_BOTTOM;

		bool found = false;

		for (int i = 0; i < 4; i++)
		{
			if (found) break;
			for (int j = 0; j < 4; j++)
			{
				x_src_new = x_src_res[i];
				y_src_new = y_src_res[i];
				x_dst_new = x_dst_res[j];
				y_dst_new = y_dst_res[j];
				if ( !isRectCollision(x_src_new, y_src_new, width_src, height_src, x_dst_new, y_dst_new, width_dst, height_dst) )
				{
					length = getCommonLength(x_src_new, width_src, x_dst_new, width_dst);
					if (length >= 0)
					{
						offset_dst = x_src_new - x_dst_new;
						
						distSqr_src = (int)( MY_SQR(x_src_new - x_src) + MY_SQR(y_src_new - y_src) );
						distSqr_dst = (int)( MY_SQR(x_dst_new - x_dst) + MY_SQR(y_dst_new - y_dst) );

						found = true;
						break;
					}
				}
			}
		}

		assert(found);
	}

	assert(length >= 0);

	return true;
}

void GameObject::addCollision(GameObject* pObject, CollisionInfo& info)
{
	assert(m_collObjsNumber < MAX_COLLISION_OBJECTS_NUMBER);
	m_ppCollObjsList[m_collObjsNumber] = pObject;
	m_pCollInfosList[m_collObjsNumber] = info;
	m_collObjsNumber++;
}

void GameObject::removeCollision(GameObject* pObject)
{
	assert(m_collObjsNumber > 0);
	for (int i = 0; i < m_collObjsNumber; i++) if (m_pCollInfosList[i].m_isCollision)
	{
		if (m_ppCollObjsList[i] == pObject)
		{
			m_pCollInfosList[i].m_isCollision = false;
			return;
		}
	}
	assert(0);
}

void GameObject::clearCollisionList()
{
	m_collObjsNumber = 0;
}

void GameObject::reduceCollisionList()
{
	//---------------------------------------------------------------------------------
	int minDistSqr = MY_MAX_INT;
	for (int i = 0; i < m_collObjsNumber; i++) if (m_pCollInfosList[i].m_isCollision)
	{
		if (m_pCollInfosList[i].m_distSqr < minDistSqr)
		{
			minDistSqr = m_pCollInfosList[i].m_distSqr;
		}
	}

	for (int i = 0; i < m_collObjsNumber; i++) if (m_pCollInfosList[i].m_isCollision)
	{
		if (m_pCollInfosList[i].m_distSqr != minDistSqr)
		{
			m_pCollInfosList[i].m_isCollision = false;
			m_ppCollObjsList[i]->removeCollision(this);
		}
	}

	//---------------------------------------------------------------------------------
	for (int i = 0; i < m_collObjsNumber; i++) if (m_pCollInfosList[i].m_isCollision)
	{
		if (m_pCollInfosList[i].m_length == 0)
		{
			for (int j = 0; j < m_collObjsNumber; j++) if (m_pCollInfosList[j].m_isCollision)
			{
				if (	m_pCollInfosList[j].m_length > 0 &&
						m_pCollInfosList[j].m_edgeResult != m_pCollInfosList[i].m_edgeResult	)
				{
					m_pCollInfosList[i].m_isCollision = false;
					m_ppCollObjsList[i]->removeCollision(this);
					break;
				}
			}
		}
	}

	//---------------------------------------------------------------------------------
	for (int i = 0; i < m_collObjsNumber - 1; i++) if (m_pCollInfosList[i].m_isCollision && m_ppCollObjsList[i]->isTile())
	{
		for (int j = i + 1; j < m_collObjsNumber; j++) if (m_pCollInfosList[j].m_isCollision && m_ppCollObjsList[j]->isTile())
		{
			if ( m_pCollInfosList[i].m_edgeResult == m_pCollInfosList[j].m_edgeResult )
			{
				TiledObject* pTile1 = (TiledObject*)m_ppCollObjsList[i];
				TiledObject* pTile2 = (TiledObject*)m_ppCollObjsList[j];
				if ( pTile1->rowIndex() == pTile2->rowIndex() )
				{
					if ( pTile1->colIndex() + 1 == pTile2->colIndex() || pTile2->colIndex() + 1 == pTile1->colIndex() )
					{
						if ( m_pCollInfosList[i].m_length < pTile1->width() >> 1 )
						{
							m_pCollInfosList[i].m_isCollision = false;
							m_ppCollObjsList[i]->removeCollision(this);
						}
						if ( m_pCollInfosList[j].m_length < pTile2->width() >> 1 )
						{
							m_pCollInfosList[j].m_isCollision = false;
							m_ppCollObjsList[j]->removeCollision(this);
						}
					}
				}
			}
		}
	}

}
