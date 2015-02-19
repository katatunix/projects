#include <Windows.h>
#include <stdio.h>

#include "../CGame.h"

#define SHOW_CONSOLE		1
#define WINDOW_RESIZABLE	1

//int g_gameWidth = 640;
//int g_gameHeight = 480;
int g_gameWidth = 1024;
int g_gameHeight = 480;

#define GAME_TITLE "Game"

#define FPS 25

//------------------------------------------------------------------------------------------
//	Global varibles
//------------------------------------------------------------------------------------------
HDC		g_hDC;
HWND	g_hWnd;

int g_lastTick;
int g_curTick;

bool g_isRunGame = false;

//------------------------------------------------------------------------------------------
//	Prototypes
//------------------------------------------------------------------------------------------
LRESULT CALLBACK WinProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam);

//------------------------------------------------------------------------------------------
//	WinMain function
//------------------------------------------------------------------------------------------

int WINAPI WinMain(HINSTANCE hInst, HINSTANCE hPrevInst,
	LPSTR lpCmdLine, int nShowCmd)
{
#if SHOW_CONSOLE
	AllocConsole();
	freopen("con", "w", stdout);
	freopen("con", "w", stderr);
#endif
	WNDCLASSEX wClass;
	ZeroMemory(&wClass, sizeof(WNDCLASSEX));
	
	wClass.cbClsExtra		= NULL;
	wClass.cbSize			= sizeof(WNDCLASSEX);
	wClass.cbWndExtra		= NULL;
	wClass.hbrBackground	= (HBRUSH) COLOR_WINDOW;
	wClass.hCursor			= LoadCursor(NULL, IDC_ARROW);
	wClass.hIcon			= NULL;
	wClass.hIconSm			= NULL;
	wClass.hInstance		= hInst;
	wClass.lpfnWndProc		= (WNDPROC) WinProc;
	wClass.lpszClassName	= "Window Class";
	wClass.lpszMenuName		= NULL;
	wClass.style			= CS_HREDRAW | CS_VREDRAW;

	if (!RegisterClassEx(&wClass))
	{
		MessageBox(NULL, "Window class creation failed", "Error", MB_ICONERROR);
		return 0;
	}

	int screenWidth		= GetSystemMetrics(SM_CXSCREEN);
	int screenHeight	= GetSystemMetrics(SM_CYSCREEN);

#if WINDOW_RESIZABLE
	const int CX_FRAME	= SM_CXFRAME;
	const int CY_FRAME	= SM_CYFRAME;
	const int DW_STYLE	= WS_OVERLAPPEDWINDOW;
#else
	const int CX_FRAME	= SM_CXFIXEDFRAME;
	const int CY_FRAME	= SM_CYFIXEDFRAME;
	const int DW_STYLE	= WS_OVERLAPPEDWINDOW & ~WS_MAXIMIZEBOX & ~WS_SIZEBOX;
#endif

	int windowWidth		= g_gameWidth + GetSystemMetrics(CX_FRAME) * 2;
	int windowHeight	= g_gameHeight + GetSystemMetrics(SM_CYCAPTION) + GetSystemMetrics(CY_FRAME) * 2;

	g_hWnd = CreateWindowEx(NULL,
		"Window Class",
		GAME_TITLE,
		DW_STYLE,
		(screenWidth - windowWidth) / 2,
		(screenHeight - windowHeight) / 2,
		windowWidth,
		windowHeight,
		NULL,
		NULL,
		hInst,
		NULL
	);

	if (!g_hWnd)
	{
		MessageBox(NULL, "Window creation failed", "Error", MB_ICONERROR);
		return 0;
	}
	
	g_hDC = GetDC(g_hWnd);
	if (!g_hDC)
	{
		MessageBox(NULL, "Failed to create the device context", "Error", MB_ICONERROR);
		return 0;
	}
	
	ShowWindow(g_hWnd, nShowCmd);

	//
	new CGame();
	g_pGame->init(g_hWnd, g_gameWidth, g_gameHeight);

	//
	g_lastTick = GetTickCount();
	MSG msg;
	ZeroMemory(&msg, sizeof(MSG));

	g_isRunGame = true;

	while (true)
	{
		if (PeekMessage(&msg, NULL, 0, 0, PM_REMOVE))
		{
			if (msg.message == WM_QUIT)
			{
				break;
			}
			TranslateMessage(&msg);
			DispatchMessage(&msg);
		}
		else
		{
			if (g_pGame->loop()) break;

			g_curTick = GetTickCount();
			int duration = g_curTick - g_lastTick;
			if (duration < 1000 / FPS)
			{
				Sleep(1000 / FPS - duration);
			}
			g_lastTick = g_curTick;
		}
	}

	CGame::freeInstance();

	ReleaseDC(g_hWnd, g_hDC);
	DestroyWindow(g_hWnd);
	return 0;
}

LRESULT CALLBACK WinProc(HWND hWnd, UINT msg, WPARAM wParam, LPARAM lParam)
{
	switch (msg)
	{
		case WM_DESTROY:
		{
			PostQuitMessage(0);
			return 0;
		}
	
		case WM_KEYDOWN:
		{
			g_pGame->onKeyDown(wParam);
			return 0;
		}

		case WM_KEYUP:
		{
			g_pGame->onKeyUp(wParam);
			return 0;
		}

		case WM_SIZE:
		{
			if (g_isRunGame)
				g_pGame->setSize( LOWORD(lParam), HIWORD(lParam) );
			return 0;
		}

		case WM_LBUTTONDOWN:
		{
			g_pGame->onMouseDown(LOWORD(lParam), HIWORD(lParam));
			return 0;
		}

		case WM_LBUTTONUP:
		{
			g_pGame->onMouseUp(LOWORD(lParam), HIWORD(lParam));
			return 0;
		}

		case WM_MOUSEMOVE:
		{
			char buffer[128];
			sprintf(buffer, "%s :: (x, y) = (%d, %d)", GAME_TITLE, LOWORD(lParam), HIWORD(lParam));
			
			SetWindowText(hWnd, buffer);

			g_pGame->onMouseHover(LOWORD(lParam), HIWORD(lParam));
			return 0;
		}
	}
	
	return DefWindowProc(hWnd, msg, wParam, lParam);
}
