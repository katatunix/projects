#ifndef _OFS_MY_FILE_STREAM_H_
#define _OFS_MY_FILE_STREAM_H_

#include "predefine.h"
#include "ReadFileInfo.h"

namespace obbfilesystem
{

class MyFileStream
{
public:
	MyFileStream();
	~MyFileStream();

	void open(const char* path, u32 start, u32 size);
	void close();

	bool isOpen() const;
	u32 size() const;
	u32 tell() const;

	u32 read(void* buffer, u32 length);
	u32 readWithOffset(void* buffer, u32 length, u32 offset);

	void seekBeg(s32 offset);
	void seekCur(s32 offset);
	void seekEnd(s32 offset);

private:
	ReadFileInfo m_info;
	u32 m_offset;
};

inline const MyFileStream& operator>>(MyFileStream& s, u16& val)
{
	s.read(&val, 2);
	return s;
}

inline const MyFileStream& operator>>(MyFileStream& s, u32& val)
{
	s.read(&val, 4);
	return s;
}

}

#endif
