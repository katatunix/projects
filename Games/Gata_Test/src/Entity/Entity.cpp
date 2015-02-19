#include "Entity.h"
#include "../World.h"
#include "../Tile/TiledObject.h"

bool Entity::checkFalling()
{
	const int k_tileWidth = m_pWorld->map().tileWidth();
	const int k_tileHeight = m_pWorld->map().tileHeight();
	const int k_epsilon = k_tileWidth / 32;
	int xx = m_px;
	//for (int xx = m_pPlayer->px() - k_epsilon; xx <= m_pPlayer->px() + k_epsilon; xx++)
	{
		int yy = m_py + m_height;

		int col1 = xx / k_tileWidth;
		int col2 = (xx + k_tileWidth - 1) / k_tileWidth;
		int row = yy / k_tileHeight;

		if ( row < m_pWorld->map().rowsNumber() )
		{
			TiledObject* p1 = m_pWorld->getTiledObject(col1, row);
			TiledObject* p2 = m_pWorld->getTiledObject(col2, row);
			if ( p1 && !p1->canCollideWith(this) && p2 && !p2->canCollideWith(this) )
			{
				//printf("newX = %d, mario_px = %d\n", col1 * m_map.tileWidth(), m_pPlayer->px());
				//m_pPlayer->setpx(xx);
				//m_pPlayer->setpx( col1 * m_map.tileWidth() );
					
				//switchToState_Jumping(0); // Start falling
				//break;

				return true;
			}
		}
	}

	return false;
}

void Entity::updateMovement()
{
	int w = m_pWorld->map().tileWidth();
	int h = m_pWorld->map().tileHeight();

	m_vx += m_ax; m_vy += m_ay;

#if 0
	if (m_vx >= w)		m_vx = w - 1;
	if (m_vx <= -w)		m_vx = -w + 1;
	if (m_vy >= h)		m_vy = h - 1;
	if (m_vy <= -h)		m_vy = -h + 1;
#endif

	m_prevpx = m_px; m_prevpy = m_py;
	
	m_px = m_px + m_vx;
	m_py = m_py + m_vy;
}
