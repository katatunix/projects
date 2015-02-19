#include "ktx.h"

/*
 * SwapEndian32: Swaps endianness in an array of 32-bit values
 */
void _ktxSwapEndian32(khronos_uint32_t* pData32, int count)
{
	int i;
	for (i = 0; i < count; ++i)
	{
		khronos_uint32_t x = *pData32;
		*pData32++ = (x << 24) | ((x & 0xFF00) << 8) | ((x & 0xFF0000) >> 8) | (x >> 24);
	}
}
