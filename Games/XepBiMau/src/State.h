#ifndef __STATE_H__
#define __STATE_H__

#include <vmsys.h>

#define MAX_STATE 10

typedef void(*funcState)();

typedef struct
{
	funcState update;
	funcState leave;
	funcState destroy;
	funcState resume;
} StateFuncs;

StateFuncs* getCurrentState();

VMINT isEmptyStateStack();

void popState();

void clearStateStack();

#endif // __STATE_H__
