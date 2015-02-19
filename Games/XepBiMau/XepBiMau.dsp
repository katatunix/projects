# Microsoft Developer Studio Project File - Name="XepBiMau" - Package Owner=<4>
# Microsoft Developer Studio Generated Build File, Format Version 6.00
# ** DO NOT EDIT **

# TARGTYPE "Win32 (x86) Application" 0x0101

CFG=XepBiMau - Win32 Debug
!MESSAGE This is not a valid makefile. To build this project using NMAKE,
!MESSAGE use the Export Makefile command and run
!MESSAGE 
!MESSAGE NMAKE /f "XepBiMau.mak".
!MESSAGE 
!MESSAGE You can specify a configuration when running NMAKE
!MESSAGE by defining the macro CFG on the command line. For example:
!MESSAGE 
!MESSAGE NMAKE /f "XepBiMau.mak" CFG="XepBiMau - Win32 Debug"
!MESSAGE 
!MESSAGE Possible choices for configuration are:
!MESSAGE 
!MESSAGE "XepBiMau - Win32 Release" (based on "Win32 (x86) Application")
!MESSAGE "XepBiMau - Win32 Debug" (based on "Win32 (x86) Application")
!MESSAGE 

# Begin Project
# PROP AllowPerConfigDependencies 0
# PROP Scc_ProjName ""
# PROP Scc_LocalPath ""
CPP=cl.exe
MTL=midl.exe
RSC=rc.exe

!IF  "$(CFG)" == "XepBiMau - Win32 Release"

# PROP BASE Use_MFC 2
# PROP BASE Use_Debug_Libraries 0
# PROP BASE Output_Dir "Release"
# PROP BASE Intermediate_Dir "Release"
# PROP BASE Target_Dir ""
# PROP Use_MFC 2
# PROP Use_Debug_Libraries 0
# PROP Output_Dir "Release"
# PROP Intermediate_Dir "Release"
# PROP Target_Dir ""
# ADD BASE CPP /nologo /MD /W3 /GX /O2 /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "_AFXDLL" /YX /FD /c
# ADD CPP /nologo /MD /W3 /GX /O2 /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /YX /FD /c
# ADD BASE MTL /nologo /D "NDEBUG" /mktyplib203 /win32
# ADD MTL /nologo /D "NDEBUG" /mktyplib203 /win32
# ADD BASE RSC /l 0x409 /d "NDEBUG" /d "_AFXDLL"
# ADD RSC /l 0x409 /d "NDEBUG" /d "_AFXDLL"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 /nologo /subsystem:windows /machine:I386
# ADD LINK32 /nologo /subsystem:windows /machine:I386

!ELSEIF  "$(CFG)" == "XepBiMau - Win32 Debug"

# PROP BASE Use_MFC 0
# PROP BASE Use_Debug_Libraries 1
# PROP BASE Output_Dir "Debug"
# PROP BASE Intermediate_Dir "Debug"
# PROP BASE Target_Dir ""
# PROP Use_MFC 0
# PROP Use_Debug_Libraries 1
# PROP Output_Dir "Debug"
# PROP Intermediate_Dir "Debug"
# PROP Target_Dir ""
# ADD BASE CPP /nologo /W3 /Gm /GX /ZI /Od /D "WIN32" /D "_DEBUG" /D "_WINDOWS" /D "_MBCS" /YX /FD /GZ /c
# ADD CPP /nologo /MTd /W3 /Gm /GR /GX /ZI /Od /D "WIN32" /D "_DEBUG" /D "_WINDOWS" /D "_MBCS" /D "_CONSOLE" /D "_MINIGUI_LIB_" /D "_USE_MINIGUIENTRY" /D "_NOUNIX_" /D "_FOR_WNC" /FR /YX /FD /GZ /c
# ADD BASE MTL /nologo /D "_DEBUG" /mktyplib203 /win32
# ADD MTL /nologo /D "_DEBUG" /mktyplib203 /win32
# ADD BASE RSC /l 0x409 /d "_DEBUG"
# ADD RSC /l 0x409 /d "_DEBUG"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 kernel32.lib user32.lib gdi32.lib winspool.lib comdlg32.lib advapi32.lib shell32.lib ole32.lib oleaut32.lib uuid.lib odbc32.lib odbccp32.lib /nologo /subsystem:windows /debug /machine:I386 /pdbtype:sept
# ADD LINK32 kernel32.lib user32.lib gdi32.lib winspool.lib comdlg32.lib advapi32.lib shell32.lib ole32.lib oleaut32.lib uuid.lib odbc32.lib odbccp32.lib vre_sim.lib vre_core.lib vre_payment3.lib ws2_32.lib winmm.lib msimg32.lib /nologo /subsystem:windows /debug /machine:I386 /pdbtype:sept /libpath:"C:\Program Files\VRE IDE\Lib"
# Begin Custom Build - build for VRE Applications
OutDir=.\Debug
TargetName=XepBiMau
InputPath=.\Debug\XepBiMau.exe
SOURCE="$(InputPath)"

"$(OUTDIR)\$(TargetName).lib" : $(SOURCE) "$(INTDIR)" "$(OUTDIR)"
	link -lib /OUT:"$(OUTDIR)\$(TargetName).lib" "$(OUTDIR)\*.obj"

# End Custom Build

!ENDIF 

# Begin Target

# Name "XepBiMau - Win32 Release"
# Name "XepBiMau - Win32 Debug"
# Begin Group "Source Files"

# PROP Default_Filter "cpp;c;cxx;rc;def;r;odl;idl;hpj;bat"
# Begin Source File

SOURCE=.\XepBiMau.c
# End Source File
# End Group
# Begin Group "Header Files"

# PROP Default_Filter "h;hpp;hxx;hm;inl"
# End Group
# Begin Group "Resource Files"

# PROP Default_Filter "ico;cur;bmp;dlg;rc2;rct;bin;rgs;gif;jpg;jpeg;jpe"
# End Group
# Begin Group "src"

# PROP Default_Filter ""
# Begin Group "GameState"

# PROP Default_Filter ""
# Begin Source File

SOURCE=.\src\GameState\GS_AskNameMenu.c
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_AskNameMenu.h
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_AskSoundMenu.c
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_AskSoundMenu.h
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_EndGameMenu.c
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_EndGameMenu.h
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_HelpMenu.c
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_HelpMenu.h
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_HighScoreMenu.c
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_HighScoreMenu.h
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_Logo.c
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_Logo.h
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_MainMenu.c
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_MainMenu.h
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_NewGameMenu.c
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_NewGameMenu.h
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_OptionMenu.c
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_OptionMenu.h
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_Run.c
# End Source File
# Begin Source File

SOURCE=.\src\GameState\GS_Run.h
# End Source File
# End Group
# Begin Group "Utils"

# PROP Default_Filter ""
# Begin Source File

SOURCE=.\src\Utils\Algorithms.c
# End Source File
# Begin Source File

SOURCE=.\src\Utils\Algorithms.h
# End Source File
# Begin Source File

SOURCE=.\src\Utils\FontBM.c
# End Source File
# Begin Source File

SOURCE=.\src\Utils\FontBM.h
# End Source File
# Begin Source File

SOURCE=.\src\Utils\FontDescriptor.c
# End Source File
# Begin Source File

SOURCE=.\src\Utils\Utils.c
# End Source File
# Begin Source File

SOURCE=.\src\Utils\Utils.h
# End Source File
# End Group
# Begin Source File

SOURCE=.\src\Audio.c
# End Source File
# Begin Source File

SOURCE=.\src\Audio.h
# End Source File
# Begin Source File

SOURCE=.\src\Define.h
# End Source File
# Begin Source File

SOURCE=.\src\Game.c
# End Source File
# Begin Source File

SOURCE=.\src\Game.h
# End Source File
# Begin Source File

SOURCE=.\src\Key.c
# End Source File
# Begin Source File

SOURCE=.\src\Key.h
# End Source File
# Begin Source File

SOURCE=.\src\State.c
# End Source File
# Begin Source File

SOURCE=.\src\State.h
# End Source File
# Begin Source File

SOURCE=.\src\Text.c
# End Source File
# End Group
# End Target
# End Project
