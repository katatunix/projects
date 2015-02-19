#include "GS_InGame.h"
#include "../CGame.h"

#include "../SpriteManager.h"
#include "../ImageManager.h"

#include "../GameObjectPool.h"

#include "../Entity/Player.h"

#include "../Tile/Map.h"

using namespace gata::graphic;
using namespace gata::gui;

GS_InGame::GS_InGame()
{
	
}

GS_InGame::~GS_InGame()
{
	
}

bool GS_InGame::isKindOf(int kind)
{
	return kind == STATE_IN_GAME;
}

void GS_InGame::create()
{
	m_scale = 1.0f;

	m_world.load("world_1_1.json");

	m_world.setCamera(0, 0, 0, 0);

	getSprite(SPRITE_font3_myspr)->setImage( getImage(IMAGE_font3_tga) );

	//
	m_pMasterWidget = new Widget(0, 0, 0, 0, 0, 0);

	m_pLeftWidget = new NavButtonWidget();
	m_pLeftWidget->setDad(m_pMasterWidget);
	/*m_pLeftWidget->setRelativePos_Original(0, 300);
	m_pLeftWidget->setSize_Original(180, 180);
	m_pLeftWidget->setIsAnchorTop(false);
	m_pLeftWidget->setIsFixedRatio(true);*/

	m_pRightWidget = new NavButtonWidget();
	m_pRightWidget->setDad(m_pMasterWidget);
	/*m_pRightWidget->setRelativePos_Original(190, 300);
	m_pRightWidget->setSize_Original(180, 180);
	m_pRightWidget->setIsAnchorTop(false);
	m_pRightWidget->setIsFixedRatio(true);*/

	m_pJumpWidget = new NavButtonWidget();
	m_pJumpWidget->setDad(m_pMasterWidget);
	/*m_pJumpWidget->setRelativePos_Original(800-180, 300);
	m_pJumpWidget->setSize_Original(180, 180);
	m_pJumpWidget->setIsAnchorLeft(false);
	m_pJumpWidget->setIsAnchorTop(false);
	m_pJumpWidget->setIsFixedRatio(true);*/

	m_pFastWidget = new NavButtonWidget();
	m_pFastWidget->setDad(m_pMasterWidget);
	/*m_pFastWidget->setRelativePos_Original(515, 330);
	m_pFastWidget->setSize_Original(100, 100);
	m_pFastWidget->setIsAnchorLeft(false);
	m_pFastWidget->setIsAnchorTop(false);
	m_pFastWidget->setIsFixedRatio(true);*/
	//

	//m_pMasterWidget->resizeTo(g_pGame->width(), g_pGame->height());

	setupWidgetsPosition();
}

void GS_InGame::destroy()
{
	SAFE_DEL(m_pMasterWidget);
	m_world.unload();
}

bool GS_InGame::update()
{
#if 1
	// Zoom type 1
	m_scale = (float)g_pGame->height() / (float)m_world.map().totalHeight();

	int newCameraWidth = (int)(g_pGame->width() / m_scale);
	if (newCameraWidth > m_world.map().totalWidth())
	{
		newCameraWidth = m_world.map().totalWidth();
	}

	m_world.setCameraSize( newCameraWidth, m_world.map().totalHeight() );
#else
	// Zoom type 2
	int screenHeight = g_pGame->height();
	
	int mapHeight = m_world.map().totalHeight();
	if (screenHeight <= mapHeight)
	{
		m_scale = 1.0f;
		m_world.setCameraSize( g_pGame->width(), screenHeight );
	}
	else if (screenHeight <= mapHeight * 3 / 2)
	{
		m_scale = 1.5f;
		m_world.setCameraSize( g_pGame->width() * 2 / 3, screenHeight * 2 / 3 );
	}
	else
	{
		m_scale = 2.0f;
		m_world.setCameraSize( g_pGame->width() / 2, screenHeight / 2 );
	}
	/*
	m_scale = 0.75f;
	m_world.setCameraSize( g_pGame->width() / m_scale, screenHeight / m_scale );
	*/
#endif

	m_world.update();

	//
	m_pMasterWidget->update();
	
	setupWidgetsPosition();

	//
	while ( g_pGame->hasMouse() )
	{
		int action, x, y, id;
		
		g_pGame->getMouseInfo(action, x, y, id);

		// cheat
		if (action == 1 && x < 50 && y < 50)
		{
			PlayingButtonWidget::m_allowRender = !PlayingButtonWidget::m_allowRender;
		}
		//------
		
		m_pMasterWidget->processMouseEvent(MouseEvent(action == 0 ? MOUSE_DOWN : MOUSE_UP, x, y, id));
	}

	//
	m_world.setLeftHolding( g_pGame->isKeyDown(KEY_LEFT) || m_pLeftWidget->isActive() );
	m_world.setRightHolding( g_pGame->isKeyDown(KEY_RIGHT) || m_pRightWidget->isActive() );
	m_world.setJumpHolding( g_pGame->isKeyDown(KEY_UP)  || m_pJumpWidget->isActive() );
	m_world.setFastHolding( g_pGame->isKeyDown(KEY_A)  || m_pFastWidget->isActive() );

	return g_pGame->isKeyDown(KEY_EXIT);
}

void GS_InGame::render()
{
	float xScaleOld, yScaleOld;
	g_pGraphic->getScale(xScaleOld, yScaleOld);

	g_pGraphic->setScale(m_scale, m_scale);

	m_world.render(g_pGraphic);

	g_pGraphic->setScale(xScaleOld, yScaleOld);

	m_pMasterWidget->render(g_pGraphic);

	g_pGame->renderFps();

#if 1
	char buffer[64];
	int x, y;
	m_world.getPlayerPos(x, y);
	sprintf(buffer, "mario(%d,%d) entities=%d", x, y, m_world.getLiveEntitiesCount());
	SpriteManager::getInstance()->getRes(SPRITE_font3_myspr)->drawString(g_pGraphic, buffer, 100, 0);
#endif
}

void GS_InGame::pause()
{
	LOGI("GS_InGame::pause()");
}

void GS_InGame::resume()
{
	LOGI("GS_InGame::resume()");
}

void GS_InGame::setupWidgetsPosition()
{
	//m_pMasterWidget->resizeTo(g_pGame->width(), g_pGame->height());
	m_pMasterWidget->setRelativePos(0, 0);
	m_pMasterWidget->setSize( g_pGame->width(), g_pGame->height() );

	int buttonSize = g_pGame->height() / 4;//180;
	const int k_spacing = 10;
	if ( g_pGame->width() < buttonSize * 4 + k_spacing * 3 )
	{
		buttonSize = (g_pGame->width() - k_spacing * 3) / 4;
	}
	
	m_pLeftWidget->setRelativePos(0, g_pGame->height() - buttonSize);
	m_pLeftWidget->setSize(buttonSize, buttonSize);

	m_pRightWidget->setRelativePos(buttonSize + k_spacing, g_pGame->height() - buttonSize);
	m_pRightWidget->setSize(buttonSize, buttonSize);

	m_pJumpWidget->setRelativePos(g_pGame->width() - buttonSize, g_pGame->height() - buttonSize);
	m_pJumpWidget->setSize(buttonSize, buttonSize);

	m_pFastWidget->setRelativePos(g_pGame->width() - buttonSize - buttonSize - k_spacing, g_pGame->height() - buttonSize);
	m_pFastWidget->setSize(buttonSize, buttonSize);
}
