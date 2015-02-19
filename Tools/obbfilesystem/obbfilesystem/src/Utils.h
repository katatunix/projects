#ifndef _OFS_UTILS_H_
#define _OFS_UTILS_H_

#include <string>
#include <vector>

#include "predefine.h"

namespace obbfilesystem
{

u32 getMemoryPageAlignment();

bool isStartWith(const char* s1, const char* s2);

std::vector<std::string> splitString(const char* str, const char* separators, bool bRemoveEmptyStrings);

}

#endif
