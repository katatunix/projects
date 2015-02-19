#pragma once

#include "IFileSystem.h"

#include <stdio.h>
#include <map>
#include <string>

namespace gata {
namespace filesystem {

class FileSystem : public IFileSystem
{
public:
	IFileSystem* provideIFileSystem();

	FileSystem();
	~FileSystem();

	int open(const char* path);
	void close(int file);

	int size(int file);
	int tell(int file);

	void seekBeg(int file, int offset);
	void seekCur(int file, int offset);
	void seekEnd(int file, int offset);
	int read(int file, int bytes, void* buf);

	void setCurrentDir(const char* path);

private:
	bool isAbsolutePath(const char* path);

	//---------------------------------------------------------

	struct MyFile
	{
		FILE* handle;
		int size;
	};

	typedef std::map<int, MyFile> MyMap;
	MyMap m_files;
	int m_count;

	std::string m_currentDir;
};

}
}
