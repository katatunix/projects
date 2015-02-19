#include <string.h>

#include "MyFileStream.h"
#include "MemMap.h"

namespace obbfilesystem
{

MyFileStream::MyFileStream()
{
}

MyFileStream::~MyFileStream()
{
	close();
}

void MyFileStream::open(const char* path, u32 start, u32 size)
{
	close();
	m_info = _mmap(path, start, size);
	m_offset = 0;
}

void MyFileStream::close()
{
	_munmap(m_info);
}

bool MyFileStream::isOpen() const
{
	return m_info.isValid();
}

u32 MyFileStream::size() const
{
	if (!m_info.isValid()) return 0;
	return m_info.accessibleSize();
}

u32 MyFileStream::tell() const
{
	if (!m_info.isValid()) return 0;
	return m_offset;
}

u32 MyFileStream::read(void* buffer, u32 length)
{
	if (length == 0) return 0;
	if (!m_info.isValid()) return 0;

	u32 remain = (u32)( size() - tell() );
	if (remain == 0) return 0;

	if (remain < length) length = remain;
	memcpy(buffer, (char*)m_info.data() + m_info.start() + m_offset, length);
	m_offset += length;
	return length;
}

u32 MyFileStream::readWithOffset(void* buffer, u32 length, u32 offset)
{
	if (length == 0) return 0;
	if (!m_info.isValid()) return 0;

	if (offset >= size()) return 0;

	u32 remain = size() - offset; // sure: remain > 0

	if (remain < length) length = remain;
	memcpy(buffer, (char*)m_info.data() + m_info.start() + offset, length);
	return length;
}

void MyFileStream::seekBeg(s32 offset)
{
	if (!m_info.isValid()) return;
	m_offset = offset < 0 ? 0 : (u32)offset;
}

void MyFileStream::seekCur(s32 offset)
{
	if (!m_info.isValid()) return;
	s32 newOffset = m_offset + offset;
	if (newOffset < 0) newOffset = 0;
	if ( (u32)newOffset > size() ) newOffset = size();
	m_offset = newOffset;
}

void MyFileStream::seekEnd(s32 offset)
{
	if (!m_info.isValid()) return;
	s32 newOffset = size() + offset;
	if (newOffset < 0) newOffset = 0;
	if ( (u32)newOffset > size() ) newOffset = size();
	m_offset = newOffset;
}

} // namespace
