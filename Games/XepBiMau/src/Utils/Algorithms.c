#include "Algorithms.h"
#include <time.h>

VMINT myrandom(VMINT n)
{
	return rand() % n;
}

VMINT myrandomLR(VMINT lo, VMINT hi)
{
	return lo + myrandom(hi - lo + 1);
}

void makeRandMatrix(VMINT** a)
{
	VMINT i, j, remain, count, count2, stop;
	srand(time(NULL));

	for (i = 0; i < BOARD_COL; i++)
	{
		for (j = 0; j < BOARD_ROW; j++)
		{
			a[i][j] = 0;
		}
	}

	/*
	a[0][0] = 1; a[1][0] = 1; a[2][0] = 1;
	a[0][1] = 1; a[1][1] = 1; a[2][1] = 1;
	a[0][2] = 1; a[1][2] = 1; a[2][2] = 1;
	a[0][3] = 1; a[1][3] = 1; a[2][3] = 1;
	a[0][4] = -1; a[1][4] = -1; a[2][4] = -1;

	a[8][8] = 2;

	return;
	*/

	count = BOARD_COL * BOARD_ROW;
	count2 = count - INIT_BALL_NUM;
	do
	{
		remain = myrandom(count--) + 1;
		stop = 0;
		for (i = 0; i < BOARD_COL; i++)
		{
			if (stop) break;
			for (j = 0; j < BOARD_ROW; j++)
			{
				if (a[i][j] == 0)
				{
					remain--;
					if (remain == 0)
					{
						a[i][j] = myrandom(MAX_COLOR) + 1;
						stop = 1;
						break;
					}
				}
			}
		}
	} while (count > count2);

	addNextColor(a);
}

void addNextColor(VMINT** a)
{
	VMINT count, tmp, i, j, remain, stop;
	count = countEmpty(a);

	srand(time(NULL));

	for (tmp = 0; tmp < NEXT_BALL_NUM; tmp++)
	{
		remain = myrandom(count--) + 1;
		stop = 0;
		for (i = 0; i < BOARD_COL; i++)
		{
			if (stop) break;
			for (j = 0; j < BOARD_ROW; j++)
			{
				if (a[i][j] == 0)
				{
					remain--;
					if (remain == 0)
					{
						a[i][j] = - (myrandom(MAX_COLOR) + 1);
						stop = 1;
						break;
					}
				}
			}
		}
	}
}

VMINT countEmpty(VMINT** a)
{
	VMINT i, j, count;
	count = 0;
	for (i = 0; i < BOARD_COL; i++)
		for (j = 0; j < BOARD_ROW; j++)
			if (a[i][j] <= 0)
				count++;
	return count;
}

PointList findPath(VMINT** a, VMINT i1, VMINT j1, VMINT i2, VMINT j2)
{
	VMINT dadi[BOARD_COL][BOARD_ROW];
	VMINT dadj[BOARD_COL][BOARD_ROW];

	VMINT queuei[BOARD_COL * BOARD_ROW];
	VMINT queuej[BOARD_COL * BOARD_ROW];
	
	VMINT u[] = {1, 0, -1, 0};
	VMINT v[] = {0, 1, 0, -1};

	VMINT fist = 0, last = 0;

	VMINT x, y, xx, yy, i, j, k;

	PointList res;
	res.len = 0;

	for (x = 0; x < BOARD_COL; x++)
		for (y = 0; y < BOARD_ROW; y++)
			dadi[x][y] = -1;

	queuei[0] = i2;
	queuej[0] = j2;
	dadi[i2][j2] = -2;

	while (fist <= last)
	{
		x = queuei[fist];
		y = queuej[fist];
		fist++;
		for (k = 0; k < 4; k++)
		{
			xx = x + u[k];
			yy = y + v[k];
			if (xx == i1 && yy == j1)
			{
				dadi[i1][j1] = x;
				dadj[i1][j1] = y;
				
				i = 0;
				while (1)
				{
					res.point[i].x = i1;
					res.point[i].y = j1;
					i++;
					k = i1;
					i1 = dadi[i1][j1];
					if (i1 == -2) break;
					j1 = dadj[k][j1];
				}
				res.len = i;
				return res;
			}
			
			if (!isInside(xx, yy)) continue;
			
			if (dadi[xx][yy] == -1 && a[xx][yy] <= 0)
			{
				last++;
				queuei[last] = xx;
				queuej[last] = yy;
				dadi[xx][yy] = x;
				dadj[xx][yy] = y;
			}
		}
	}
	
	return res;
}

VMINT isInside(VMINT i, VMINT j)
{
	return (i >= 0 && i < BOARD_COL && j >= 0 && j < BOARD_ROW);
}

PointList checkBall(VMINT** a, VMINT iCenter, VMINT jCenter, GameType type)
{
	switch (type)
	{
	case TYPE_LINE:
		return checkLines(a, iCenter, jCenter);
	case TYPE_SQUARE:
		return checkSquares(a, iCenter, jCenter);
	default:
		return checkBlocks(a, iCenter, jCenter);
	}
}

PointList checkLines(VMINT** a, VMINT iCenter, VMINT jCenter)
{
	PointList lines;

	VMINT u[] = {0, 1, 1, 1};
	VMINT v[] = {1, 0, -1, 1};
	VMINT i, j, k, t, count;
	count = 0;
	
	for (t = 0; t < 4; t++)
	{
		k = 0;
		i = iCenter;
		j = jCenter;
		while (1)
		{
			i += u[t];
			j += v[t];
			if (!isInside(i, j))
				break;
			if (a[i][j] != a[iCenter][jCenter])
				break;
			k++;
		}
		i = iCenter;
		j = jCenter;
		while (1)
		{
			i -= u[t];
			j -= v[t];
			if (!isInside(i, j))
				break;
			if (a[i][j] != a[iCenter][jCenter])
				break;
			k++;
		}
		k++;
		if (k >= EAT_BALL_LINE_NUM)
		{
			while (k-- > 0)
			{
				i += u[t];
				j += v[t];
				if (i != iCenter || j != jCenter)
				{
					lines.point[count].x = i;
					lines.point[count].y = j;
					count++;
				}
			}
		}
	}

	if (count > 0)
	{
		lines.point[count].x = iCenter;
		lines.point[count].y = jCenter;
		lines.len = count + 1;
	}
	else
	{
		lines.len = 0;
	}
	return lines;
}

PointList checkSquares(VMINT** a, VMINT iCenter, VMINT jCenter)
{
	PointList squares;	

	VMINT u[] = {-1, 0, 1, 1, 1, 0, -1, -1};
	VMINT v[] = {-1, -1, -1, 0, 1, 1, 1, 0};
	VMINT mark[] = {0, 0, 0, 0, 0, 0, 0, 0};
	VMINT eatRect[][5] =
	{
		{1, 2, 3,  4,  5},
		{3, 4, 5,  6,  7},
		{5, 6, 7,  0,  1},
		{7, 0, 1,  2,  3},
		{1, 2, 3, -1, -1},
		{3, 4, 5, -1, -1},
		{5, 6, 7, -1, -1},
		{7, 0, 1, -1, -1},
	};
	VMINT color = a[iCenter][jCenter];
	VMINT x, y, k, i, j;

	squares.len = 0;

	for (k = 0; k < 8; k++)
	{
		x = iCenter + u[k];
		y = jCenter + v[k];
		if (isInside(x, y) && a[x][y] == color)
		{
			mark[k] = 1;
		}
	}

	for (k = 0; k < 8; k++)
	{
		x = k <= 3 ? 5 : 3;
		j = 1;
		for (i = 0; i < 5; i++)
		{
			if (!mark[eatRect[k][i]])
			{
				j = 0;
				break;
			}
		}
		if (j)
		{
			squares.len = 1;
			squares.point[0].x = iCenter;
			squares.point[0].y = jCenter;
			for (i = 0; i < x; i++)
			{
				squares.point[squares.len].x = iCenter + u[eatRect[k][i]];
				squares.point[squares.len].y = jCenter + v[eatRect[k][i]];
				squares.len++;
			}
			return squares;
		}
	}
	
	return squares;
}

PointList checkBlocks(VMINT** a, VMINT iCenter, VMINT jCenter)
{
	PointList blocks;

	VMINT queuei[BOARD_COL * BOARD_ROW];
	VMINT queuej[BOARD_COL * BOARD_ROW];
	
	VMINT mark[BOARD_COL][BOARD_ROW];
	
	VMINT u[] = {1, 0, -1, 0};
	VMINT v[] = {0, 1, 0, -1};

	VMINT i, j, color, x, y, xx, yy, k;
	VMINT fist = 0, last = 0;
	
	for (i = 0; i < BOARD_COL; i++)
		for (j = 0; j < BOARD_ROW; j++)
			mark[i][j] = 1;

	mark[iCenter][jCenter] = 0;
	color = a[iCenter][jCenter];

	queuei[fist] = iCenter;
	queuej[fist] = jCenter;
	
	blocks.len = 1;

	blocks.point[0].x = iCenter;
	blocks.point[0].y = jCenter;
	
	while (fist <= last)
	{
		x = queuei[fist];
		y = queuej[fist];
		fist++;
		for (k = 0; k < 4; k++)
		{
			xx = x + u[k];
			yy = y + v[k];
			if (!isInside(xx, yy)) continue;
			if (mark[xx][yy] && a[xx][yy] == color)
			{
				last++;
				queuei[last] = xx;
				queuej[last] = yy;
				mark[xx][yy] = 0;
				blocks.point[blocks.len].x = xx;
				blocks.point[blocks.len].y = yy;
				blocks.len++;
			}
		}
	}
	
	if (blocks.len < EAT_BALL_BLOCK_NUM)
	{
		blocks.len = 0;
	}
	
	return blocks;
}

PointList mergeBall(PointList list1, PointList list2)
{
	VMINT i;
	VMINT len1 = list1.len;
	for (i = 0; i < list2.len; i++)
	{
		if (!isExist(list2.point[i], list1))
		{
			list1.point[len1++] = list2.point[i];
		}
	}
	list1.len = len1;
	
	return list1;
}

VMINT isExist(Point p, PointList list)
{
	VMINT i;
	for (i = 0; i < list.len; i++)
	{
		if (p.x == list.point[i].x && p.y == list.point[i].y)
			return 1;
	}
	return 0;
}

VMINT calcuScore(VMINT balls, GameType type)
{
	VMINT min;
	switch (type)
	{
	case TYPE_LINE:
		min = EAT_BALL_LINE_NUM;
		break;
	case TYPE_SQUARE:
		min = EAT_BALL_SQUARE_NUM;
		break;
	default:
		min = EAT_BALL_BLOCK_NUM;
	}

	if (balls < min) return 0;
	return min + (balls - min) * 2;
}
