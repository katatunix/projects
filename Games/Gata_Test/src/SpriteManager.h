#ifndef _SPRITE_MANAGER_H_
#define _SPRITE_MANAGER_H_

#include <gata/core/ResManager.h>
#include <gata/sprite/Sprite.h>

#include "auto_generated/sprites_define.h"

using namespace gata::core;
using namespace gata::sprite;

typedef ResManager<Sprite> SpriteManager;

#define getSprite(id) SpriteManager::getInstance()->getRes(id)

extern const char* g_pszSpritesList[];

#endif
