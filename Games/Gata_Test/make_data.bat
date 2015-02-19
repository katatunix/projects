@echo off

if exist bin\data rd /s /q bin\data
md bin\data

xcopy /E /s /q data\*.* bin\data

set KONV=%CD%\tools\Konv.exe
set SPRITE_DEF=%CD%\tools\SpriteDef.exe

rem =========================
set IMAGE_H=%CD%\src\auto_generated\images_define.h
set IMAGE_C=%CD%\src\auto_generated\images_define.cpp

if exist %IMAGE_H% del %IMAGE_H%
if exist %IMAGE_C% del %IMAGE_C%

rem =========================
set SPRITE_H=%CD%\src\auto_generated\sprites_define.h
set SPRITE_C=%CD%\src\auto_generated\sprites_define.cpp

if exist %SPRITE_H% del %SPRITE_H%
if exist %SPRITE_C% del %SPRITE_C%

rem =========================
cd bin\data
%KONV% . -f tga pvr -p IMAGE -v g_pszImagesList -h %IMAGE_H% -c %IMAGE_C%
%KONV% . -f myspr -p SPRITE -v g_pszSpritesList -h %SPRITE_H% -c %SPRITE_C%

cd ..\..\data

%SPRITE_DEF% mario_large.myspr %SPRITE_H%
%SPRITE_DEF% tiles.myspr %SPRITE_H%
%SPRITE_DEF% enemies.myspr %SPRITE_H%
%SPRITE_DEF% other.myspr %SPRITE_H%

rem =========================
cd ..
