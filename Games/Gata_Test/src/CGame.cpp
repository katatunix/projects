#include "CGame.h"

#include "ImageManager.h"
#include "SpriteManager.h"

#include <gata/gui/Widget.h>

#include "GameState/GS_Logo.h"
#include "GameState/GS_InGame.h"

CGame::CGame() : Game()
{
	
}

void CGame::init()
{
	assert( m_pGraphic->isInit() );

	new ImageManager(COUNT_IMAGE, g_pszImagesList);
	new SpriteManager(COUNT_SPRITE, g_pszSpritesList);

	getSprite(SPRITE_font3_myspr)->setImage( getImage(IMAGE_font3_tga) );

	gata::gui::Widget::init();

	//pushState(new GS_Logo());
	pushState(new GS_InGame());

	m_isInitAlready = true;
}

CGame::~CGame()
{
	clearState();

	SpriteManager::freeInstance();
	ImageManager::freeInstance();
}

void CGame::renderFps()
{
	char buffer[16];
	sprintf(buffer, "%.2f", fps());
	
	SpriteManager::getInstance()->getRes(SPRITE_font3_myspr)->drawString(g_pGraphic, buffer, 0, 0);
}
