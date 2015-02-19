#pragma once

namespace gata {
namespace core {

template<typename T>
class Ref
{
public:
	Ref() : p(0)
	{
	}
	T* get()
	{
		return p;
	}
	void set(T* q)
	{
		p = q;
	}
private:
	T* p;
};

}
}
