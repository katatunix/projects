#include "State.h"

StateFuncs g_StateStack[MAX_STATE];
VMINT g_TopStateStack;

StateFuncs* getCurrentState()
{
	if (0 <= g_TopStateStack && g_TopStateStack < MAX_STATE)
		return &g_StateStack[g_TopStateStack];
	else
		return NULL;
}

VMINT isEmptyStateStack()
{
	return g_TopStateStack < 0;
}

void popState()
{
	StateFuncs* s;
	if (isEmptyStateStack()) return;
	
	s = getCurrentState();
	s->leave();
	s->destroy();

	g_TopStateStack--;
	if (!isEmptyStateStack())
	{
		s = getCurrentState();
		s->resume();
	}
}

void clearStateStack()
{
	while (!isEmptyStateStack())
	{
		popState();
	}
}
