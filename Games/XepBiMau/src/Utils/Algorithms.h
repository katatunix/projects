#ifndef _ALGORIHTMS_H_
#define _ALGORIHTMS_H_

#include <vmsys.h>

#include "..\Define.h"

VMINT myrandom(VMINT n);
VMINT myrandomLR(VMINT lo, VMINT hi);

void makeRandMatrix(VMINT** a);

void addNextColor(VMINT** a);

VMINT countEmpty(VMINT** a);

PointList findPath(VMINT** a, VMINT i1, VMINT j1, VMINT i2, VMINT j2);

VMINT isInside(VMINT i, VMINT j);

PointList checkBall(VMINT** a, VMINT iCenter, VMINT jCenter, GameType type);

PointList checkLines(VMINT** a, VMINT iCenter, VMINT jCenter);
PointList checkSquares(VMINT** a, VMINT iCenter, VMINT jCenter);
PointList checkBlocks(VMINT** a, VMINT iCenter, VMINT jCenter);

PointList mergeBall(PointList list1, PointList list2);

VMINT isExist(Point p, PointList list);

VMINT calcuScore(VMINT balls, GameType type);

#endif
