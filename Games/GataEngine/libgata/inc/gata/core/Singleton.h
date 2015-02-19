#pragma once

#include <cassert>

namespace gata {
namespace core {

template<typename T>
class Singleton
{
public:
	Singleton()
	{
		assert(!s_instance);
		s_instance = (T*)this;
	}

	~Singleton()
	{
		assert(s_instance);
		s_instance = 0;
	}

	static T* getInstance()
	{
		assert(s_instance);
		return s_instance;
	}

	static void freeInstance()
	{
		assert(s_instance);
		delete s_instance;
		s_instance = 0;
	}

private:
	static T* s_instance;
};

template<typename T> T* Singleton<T>::s_instance = 0;

}
}
