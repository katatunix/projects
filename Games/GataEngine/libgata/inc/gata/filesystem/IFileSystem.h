#pragma once

namespace gata {
namespace filesystem {

class IFileSystem
{
public:
	virtual int open(const char* path) = 0;
	virtual void close(int file) = 0;

	virtual void seekBeg(int file, int offset) = 0;
	virtual void seekCur(int file, int offset) = 0;
	virtual void seekEnd(int file, int offset) = 0;
	virtual int read(int file, int bytes, void* buf) = 0;
	virtual int size(int file) = 0;
	virtual int tell(int file) = 0;

	virtual void setCurrentDir(const char* path) = 0;
};

}
}
