//
//
//  Generated by StarUML(tm) C++ Add-In
//
//  @ Project : Untitled
//  @ File Name : GraphicMain.cpp
//  @ Date : 6/15/2012
//  @ Author : 
//
//


#include <gata/core/macro.h>

#include <gata/graphic/GraphicMain.h>
#include <gata/graphic/Factory.h>


#include "gles20_impl/Gles20Factory.h"


namespace gata {
	namespace graphic {
//=============================================================================
bool GraphicMain::m_sIsInitOk = false;
Factory* GraphicMain::m_spFactory = 0;

void GraphicMain::init()
{
	if (m_sIsInitOk) return;

	m_spFactory = new Gles20Factory();

	m_sIsInitOk = true;
}

void GraphicMain::uninit()
{
	if (!m_sIsInitOk) return;
	SAFE_DEL(m_spFactory);
	m_sIsInitOk = false;
}

Factory* GraphicMain::getFactory()
{
	return m_spFactory;
}
//=============================================================================
	}
}