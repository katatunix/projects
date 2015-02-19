#ifndef _OFS_READ_FILE_INFO_H_
#define _OFS_READ_FILE_INFO_H_

#include "predefine.h"
#ifdef WIN32
	#include <windows.h>
#else
#endif

namespace obbfilesystem
{

class ReadFileInfo
{
public:
	ReadFileInfo() :
#ifdef WIN32
		m_handle(NULL),
		m_handleMapping(NULL),
#endif
		m_data(NULL), m_start(0), m_size(0)
	{
	}

	inline void reset()
	{
#ifdef WIN32
		m_handle = NULL;
		m_handleMapping = NULL;
#endif
		m_data = NULL;
		m_start = 0;
		m_size = 0;
	}

	inline bool isValid() const
	{
		return m_data != NULL;
	}

	inline void* data()
	{
		return m_data;
	}

	inline void data(void* p)
	{
		m_data = p;
	}

	inline u32 start()
	{
		return m_start;
	}

	inline void start(u32 v)
	{
		m_start = v;
	}

	inline void size(u32 v)
	{
		m_size = v;
	}

	inline u32 size()
	{
		return m_size;
	}

	inline u32 accessibleSize() const
	{
		return isValid() ? m_size - m_start : 0;
	}

#ifdef WIN32
	inline void handles(HANDLE h1, HANDLE h2)
	{
		m_handle = h1;
		m_handleMapping = h2;
	}

	inline HANDLE handle()
	{
		return m_handle;
	}

	inline HANDLE handleMapping()
	{
		return m_handleMapping;
	}
#endif

private:
#ifdef WIN32
	HANDLE m_handle;
	HANDLE m_handleMapping;
#endif
	// We should access from data+start to data+size
	void*	m_data;
	u32		m_start;
	u32		m_size;
};

}
#endif
