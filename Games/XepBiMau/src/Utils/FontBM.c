#include "FontBM.h"
#include "Utils.h"

#define FONT_LARGE_FILE		"font_bm_large.gif"
#define FONT_MEDIUM_FILE	"font_bm_medium.gif"
#define FONT_SMALL_FILE		"font_bm_small.gif"
#define NUM_FILE			"num.gif"

#define NUM_WIDTH	10
#define NUM_HEIGHT	15

extern VMUINT16*	g_ScreenBuffer;

extern FontDescriptor fontDescLarge;
extern FontDescriptor fontDescMedium;
extern FontDescriptor fontDescSmall;

Image fontImgLarge;
Image fontImgMedium;
Image fontImgSmall;

Image numImg;

void fontBMInit()
{
	loadImage(&fontImgLarge, FONT_LARGE_FILE);
	loadImage(&fontImgMedium, FONT_MEDIUM_FILE);
	loadImage(&numImg, NUM_FILE);
}

void fontBMFree()
{
	unloadImage(&fontImgLarge);
	unloadImage(&fontImgMedium);
	unloadImage(&numImg);
}

void fontBMDrawWrapText(FontName font, VMCHAR* string, VMINT desX, VMINT desY, VMINT www, VMINT lineH)
{
	FontDescriptor* fontDesc;
	Image* fontImg;
	CharFont cf;
	VMINT len, x, y, i, j, width;

	len = 0;
	while (string[len] != -2)
	{	
		len++;
	}
	if (len == 0) return;
	
	switch (font)
	{
	case FONT_LARGE:
		fontDesc = &fontDescLarge;
		fontImg = &fontImgLarge;
		break;
	case FONT_MEDIUM:
		fontDesc = &fontDescMedium;
		fontImg = &fontImgMedium;
		break;
	default:
		fontDesc = &fontDescSmall;
		fontImg = &fontImgSmall;
	}

	x = desX;
	y = desY;

	for (i = 0; i < len; i++)
	{
		if (string[i] == -1)
		{
			x += fontDesc->spaceWidth + fontDesc->charBetween;
		}
		else
		{
			j = i;
			width = 0;
			while (string[j] != -1 && string[j] != -2)
			{
				width += fontDesc->c[string[j]].width;
				j++;
			}
			if (x + width > desX + www)
			{
				x = desX;
				y += lineH;
			}

			cf = fontDesc->c[string[i]];
			vm_graphic_blt(
					(VMBYTE*) g_ScreenBuffer,
					x + cf.xoffset, y + cf.yoffset,
					fontImg->buffer,
					cf.x, cf.y, cf.width, cf.height,
					1
			);
			
			x += cf.width + fontDesc->charBetween;
		}
		if (x > desX + www)
		{
			x = desX;
			y += lineH;
		}
	}
}

void fontBMDrawString(FontName font, VMCHAR* string, VMINT desX, VMINT desY, VMINT anchor)
{
	FontDescriptor* fontDesc;
	Image* fontImg;
	CharFont cf;
	VMINT width, height, len, x, y, i;
	width = 0;
	len = 0;
	height = 0;
	
	switch (font)
	{
	case FONT_LARGE:
		fontDesc = &fontDescLarge;
		fontImg = &fontImgLarge;
		break;
	case FONT_MEDIUM:
		fontDesc = &fontDescMedium;
		fontImg = &fontImgMedium;
		break;
	default:
		fontDesc = &fontDescSmall;
		fontImg = &fontImgSmall;
	}

	while (string[len] != -2)
	{
		if (string[len] == -1)
		{
			width += fontDesc->spaceWidth + fontDesc->charBetween;
		}
		else
		{
			width += fontDesc->c[string[len]].width + fontDesc->charBetween;
			if (fontDesc->c[string[len]].height > height)
			{	
				height = fontDesc->c[string[len]].height;
			}
		}
		
		len++;
	}

	if (len == 0 || height == 0) return;
	
	width -= fontDesc->charBetween;

	x = desX;
	y = desY;

	if (anchor & LEFT)			x = desX;
	else if (anchor & RIGHT)	x = desX - width;
	else if (anchor & HCENTER)	x = desX - (width >> 1);

	if (anchor & TOP)			y = desY;
	else if (anchor & BOTTOM)	y = desY - height;
	else if (anchor & VCENTER)	y = desY - (height >> 1);

	for (i = 0; i < len; i++)
	{
		if (string[i] == -1)
		{
			x += fontDesc->spaceWidth + fontDesc->charBetween;
		}
		else
		{
			cf = fontDesc->c[string[i]];
			vm_graphic_blt(
					(VMBYTE*) g_ScreenBuffer,
					x + cf.xoffset, y + cf.yoffset,
					fontImg->buffer,
					cf.x, cf.y, cf.width, cf.height,
					1
			);
			
			x += cf.width + fontDesc->charBetween;
		}
	}
}

void fontDrawNumber(VMINT number, VMINT desX, VMINT desY, VMINT anchor)
{
	VMINT digit[10];
	VMINT len, k;
	VMINT x, y, width;
	len = 0;
	while (number > 0)
	{
		digit[len++] = number % 10;
		number /= 10;
	}
	if (len == 0)
	{
		len = 1;
		digit[0] = 0;
	}
	for (k = len; k < 4; k++)
		digit[k] = 0;
	len = 4;

	width = len * NUM_WIDTH;
	x = desX;
	y = desY;

	if (anchor & LEFT)			x = desX;
	else if (anchor & RIGHT)	x = desX - width;
	else if (anchor & HCENTER)	x = desX - (width >> 1);

	if (anchor & TOP)			y = desY;
	else if (anchor & BOTTOM)	y = desY - NUM_HEIGHT;
	else if (anchor & VCENTER)	y = desY - (NUM_HEIGHT >> 1);

	for (k = len - 1; k >= 0; k--)
	{
		vm_graphic_blt( (VMBYTE*) g_ScreenBuffer, x, y, numImg.buffer,
				0, NUM_HEIGHT * digit[k], NUM_WIDTH, NUM_HEIGHT, 1 );
		x += NUM_WIDTH;
	}
}

void fontBMDrawNumber(FontName font, VMINT number, VMINT desX, VMINT desY, VMINT anchor, VMINT hasDot)
{
	VMCHAR digit[10];
	VMCHAR t;
	VMINT len, i, j;
	len = 0;
	while (number > 0)
	{
		digit[len++] = (VMCHAR) (number % 10);
		number /= 10;
	}
	if (len == 0)
	{
		len = 1;
		digit[0] = 0;
	}
	
	i = 0;
	j = len - 1;
	while (i < j)
	{
		t = digit[i];
		digit[i] = digit[j];
		digit[j] = t;
		i++;
		j--;
	}
	if (hasDot)
	{
		digit[len] = 10;
		len++;
	}
	digit[len] = -2;
	fontBMDrawString(font, digit, desX, desY, anchor);
}
