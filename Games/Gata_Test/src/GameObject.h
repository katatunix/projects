#ifndef _GAME_OBJECT_H_
#define _GAME_OBJECT_H_

#include <gata/graphic/Graphic.h>

#include "auto_generated/game_objects_list.h"

#define MAX_COLLISION_OBJECTS_NUMBER 32

#define EDGE_LEFT		(-1)
#define EDGE_RIGHT		(1)
#define EDGE_TOP		(-2)
#define EDGE_BOTTOM		(2)
#define EDGE_NONE		(0)

typedef struct _CollisionInfo
{
	bool	m_isCollision;
	int		m_edgeResult;
	int		m_offset;
	int		m_length;
	int		m_distSqr;
	int		m_newX;
	int		m_newY;
} CollisionInfo;

class GameObject
{
public:
	GameObject() :					m_kind(KIND_NONE),
									m_px(0), m_py(0),
									m_width(0), m_height(0),
									m_prevpx(0), m_prevpy(0),
									m_isLive(false),
									m_collObjsNumber(0)
	{
	}

	GameObject(int kind) :			m_kind(kind),
									m_px(0), m_py(0),
									m_width(0), m_height(0),
									m_prevpx(0), m_prevpy(0),
									m_isLive(false),
									m_collObjsNumber(0)
	{
	}

	//
	void setpx(int x) { m_px = x; }
	void setpy(int y) { m_py = y; }
	void setprevpx(int x) { m_prevpx = x; }
	void setprevpy(int y) { m_prevpy = y; }
	void setLocation(int x, int y) { m_px = x; m_py = y; }
	void setWidth(int w) { m_width = w; }
	void setHeight(int h) { m_height = h; }
	void setSize(int w, int h) { m_width = w; m_height = h; }
	void setBound(int x, int y, int w, int h) {	m_px = x; m_py = y; m_width = w; m_height = h; }
	inline int px() const { return m_px; }
	inline int py() const { return m_py; }
	inline int prevpx() const { return m_prevpx; }
	inline int prevpy() const { return m_prevpy; }
	inline int width() const { return m_width; }
	inline int height() const { return m_height; }

	//
	bool isKindOf(int kind) const { return m_kind == kind; }
	inline int getKind() const { return m_kind; }
	void setKind(int kind) { m_kind = kind; }

	bool isPlayer() const { return KIND_PLAYER_BEGIN < m_kind && m_kind < KIND_PLAYER_END; }
	bool isEnemy() const { return KIND_ENEMY_BEGIN < m_kind && m_kind < KIND_ENEMY_END; }
	bool isTile() const { return KIND_TILE_BEGIN < m_kind && m_kind < KIND_TILE_END; }
	bool isOther() const { return KIND_OTHER_BEGIN < m_kind && m_kind < KIND_OTHER_END; }

	//
	inline bool isLive() const { return m_isLive; }
	void setLive(bool live) { m_isLive = live; }

	//
	virtual void render(gata::graphic::Graphic* g) = 0;
	virtual void update() = 0;

	virtual void solveCollision() = 0;
	virtual bool canCollideWith(const GameObject* other) const = 0;

	//
	void addCollision(GameObject* pObject, CollisionInfo& info);
	void removeCollision(GameObject* pObject);

	inline bool hasCollision() const { return m_collObjsNumber > 0; }
	void clearCollisionList();

	void reduceCollisionList();
	
	//
	static bool checkCollision(const GameObject* srcObj, const GameObject* dstObj,
				int& edgeResult_dst, int& distSqr_src, int& distSqr_dst, int& offset_dst, int& length,
				int& x_src_new, int& y_src_new, int& x_dst_new, int& y_dst_new);

protected:
	int				m_kind;

	int				m_px, m_py;
	int				m_width, m_height;
	int				m_prevpx, m_prevpy;

	bool			m_isLive;

	GameObject*		m_ppCollObjsList[MAX_COLLISION_OBJECTS_NUMBER];
	CollisionInfo	m_pCollInfosList[MAX_COLLISION_OBJECTS_NUMBER];
	int				m_collObjsNumber;
};

#endif
