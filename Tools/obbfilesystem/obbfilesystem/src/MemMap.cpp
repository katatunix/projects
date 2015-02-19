#include "MemMap.h"
#include "Utils.h"

#ifdef WIN32
	#include <windows.h>
#else
	#include <sys/types.h>
	#include <dirent.h>
	#include <errno.h>
	#include <fcntl.h>
	#include <sys/types.h>
	#include <sys/stat.h>
	#include <sys/mman.h>
	#include <unistd.h>
#endif

namespace obbfilesystem
{

/**
	size == 0: map entire file
**/
ReadFileInfo _mmap(const char* filePath, u32 start, u32 size)
{
	ReadFileInfo ret;
	if (!filePath) return ret;

#ifdef WIN32
	HANDLE file = NULL;
	HANDLE fileMapping = NULL;

	file = CreateFile(filePath, GENERIC_READ, FILE_SHARE_READ, NULL,
								OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, NULL);
	if (file == INVALID_HANDLE_VALUE)
	{
		file = NULL;
		goto my_end;
	}

	DWORD fileSizeHigh;
	DWORD fileSize = GetFileSize(file, &fileSizeHigh);
	if (fileSize == 0)
	{
		goto my_end;
	}

	if (size == 0) size = fileSize;
	if (start + size > fileSize)
	{
		goto my_end;
	}

	u32 pageAlignment = getMemoryPageAlignment();
	u32 alignedStart = start - (start % pageAlignment);

	fileMapping = CreateFileMapping(file, NULL, PAGE_READONLY, 0, 0, NULL);

	if (!fileMapping)
	{
		goto my_end;
	}

	void* address = MapViewOfFile(fileMapping, FILE_MAP_READ, 0, alignedStart, size + start - alignedStart);
	if (address)
	{
		ret.data(address);
		ret.handles(file, fileMapping);
		ret.start(start - alignedStart);
		ret.size(size + start - alignedStart);
	}

	my_end:

	if (!ret.isValid())
	{
		if (fileMapping) CloseHandle(fileMapping);
		if (file) CloseHandle(file);
	}
#else
	void* address;
	u32 pageAlignment;
	u32 alignedStart;
	struct stat sb;
	int fd;

	fd = open(filePath, O_RDONLY);
	if (fd == -1) 
	{
		goto my_end;
	}
	
	if (fstat(fd, &sb) == -1)
	{
		goto my_end;
	}

	if (size == 0) size = sb.st_size;
	if (start + size > sb.st_size)
	{
		goto my_end;
	}

	pageAlignment = getMemoryPageAlignment();
	alignedStart = start - (start % pageAlignment);

	address = mmap(0, size + start - alignedStart, PROT_READ, MAP_SHARED, fd, alignedStart);
	if (address == MAP_FAILED) 
	{
		goto my_end;
	}

	ret.data(address);
	ret.start(start - alignedStart);
	ret.size(size + start - alignedStart);

	my_end:
	
	if (fd != -1) close(fd);
	
#endif

	return ret;
}

void _munmap(ReadFileInfo& info)
{
	if (info.isValid())
	{
#ifdef WIN32
		UnmapViewOfFile(info.data());
		CloseHandle(info.handleMapping());
		CloseHandle(info.handle());
#else
		munmap(info.data(), info.size());
#endif
	}
}

}
