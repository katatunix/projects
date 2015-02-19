#ifndef _OFS_MEM_MAP_H_
#define _OFS_MEM_MAP_H_

#include "predefine.h"
#include "ReadFileInfo.h"

namespace obbfilesystem
{

ReadFileInfo _mmap(const char* filePath, u32 start, u32 size);

void _munmap(ReadFileInfo& info);

}

#endif
