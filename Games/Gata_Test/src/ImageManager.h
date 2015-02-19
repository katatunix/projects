#ifndef _IMAGE_MANAGER_H_
#define _IMAGE_MANAGER_H_

#include <gata/core/ResManager.h>
#include <gata/graphic/Image.h>

#include "auto_generated/images_define.h"

using namespace gata::core;
using namespace gata::graphic;

typedef ResManager<Image> ImageManager;

#define getImage(id) ImageManager::getInstance()->getRes(id)

extern const char* g_pszImagesList[];

#endif
