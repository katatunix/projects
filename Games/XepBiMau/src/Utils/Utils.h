#ifndef __UTILS_H__
#define __UTILS_H__

#include "vmio.h"
#include "vmsys.h"
#include "vmres.h"
#include "vmgraph.h"

typedef struct
{
	VMINT handle;
	VMUINT8* buffer;

	VMUINT16 width;
	VMUINT16 height;
} Image;

void loadImage( Image* p, char* filename );
void unloadImage( Image* p );

#endif // __UTILS_H__
