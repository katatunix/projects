#include "Utils.h"

#ifdef WIN32
	#include <windows.h>
#else
	#include <unistd.h>
	#include <string.h>
#endif

namespace obbfilesystem
{

u32 g_memoryPageAlignment = 0;

u32 getMemoryPageAlignment()
{
	if (g_memoryPageAlignment == 0)
	{
		#ifdef WIN32
			SYSTEM_INFO info;
			GetSystemInfo(&info);
			g_memoryPageAlignment = info.dwAllocationGranularity;
		#else
			g_memoryPageAlignment = sysconf(_SC_PAGESIZE);
		#endif
	}
	return g_memoryPageAlignment;
}

bool isSlash(char c)
{
	return c == '\\' || c == '/';
}

bool isStartWith(const char* s1, const char* s2)
{
	u32 len2 = strlen(s2);
	if (strlen(s1) < len2) return false;
	for (u32 i = 0; i < len2; i++)
	{
		if	(
				s1[i] != s2[i]
				&&
				( !isSlash(s1[i]) || !isSlash(s2[i]) )
			)
		{
			return false;
		}
	}
	return true;
}

std::vector<std::string> splitString(const char* str, const char* separators, bool bRemoveEmptyStrings)
{
	std::vector<std::string> result;
	const char* p = str;
	const char* start = p;
	int len=0;
	bool bSeparator;
	int separatorsLen = strlen(separators);
	while (*p)
	{
		bSeparator=false;
		int pVal=*p;
		for (int i=0;i<(int)separatorsLen;i++)
			if (*p==separators[i])
			{
				if (!bRemoveEmptyStrings|| (bRemoveEmptyStrings && len >0))
				{
					char* aux = new char[len+1];
					memcpy(aux,start,len);
					aux[len]='\0';
					std::string token=std::string(aux);
					delete[] aux;
					result.push_back(token);
				}
				start =p+1;				
				p++;
				len=0;
				bSeparator=true;
			}
			if (!bSeparator)
			{
				len++;
				p++;
			}
	}
	if (len>0)
	{
		char* aux = new char[len+1];
		memcpy(aux,start,len);
		aux[len]='\0';
		std::string token=std::string(aux);
		delete[] aux;
		result.push_back(token);
	}
	return result;

}

} // namespace
