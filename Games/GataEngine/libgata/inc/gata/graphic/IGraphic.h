#pragma once

class IGraphic
{
public:
	virtual void setSurface(int surfaceHandle) = 0;
	virtual void clear() = 0;

};
