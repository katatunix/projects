#ifndef _FONT_BM_H_
#define _FONT_BM_H_

#include <vmsys.h>

#define LEFT	1
#define RIGHT	2
#define HCENTER	4
#define VCENTER	8
#define TOP		16
#define BOTTOM	32

#define MAX_CHAR_FONT_NUMBER 128

typedef enum
{
	FONT_LARGE,
	FONT_MEDIUM,
	FONT_SMALL,
} FontName;

typedef struct
{
	VMUINT8 x, y, width, height;
	VMINT8 xoffset, yoffset;
} CharFont;

typedef struct
{
	VMUINT8 number;
	VMUINT8 spaceWidth;
	VMUINT8 charBetween;
	CharFont c[MAX_CHAR_FONT_NUMBER];
} FontDescriptor;

void fontBMInit();
void fontBMFree();

void fontBMDrawWrapText(FontName font, VMCHAR* string, VMINT desX, VMINT desY, VMINT www, VMINT lineH);
void fontBMDrawString(FontName font, VMCHAR* string, VMINT desX, VMINT desY, VMINT anchor);
void fontDrawNumber(VMINT number, VMINT desX, VMINT desY, VMINT anchor);
void fontBMDrawNumber(FontName font, VMINT number, VMINT desX, VMINT desY, VMINT anchor, VMINT hasDot);

#endif
