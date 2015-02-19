#include <stdio.h>
#include <string>
#include <vector>

#include <Utils.h>
#include <ObbFileSystem.h>

#include <conio.h>

using namespace obbfilesystem;

void main()
{
	ObbFileSystem ofs;
	ofs.setBaseFolderPath("z:\\Projects\\A8\\");
	ofs.setObbFilePath("z:\\Projects\\A8\\main.sa2.Asphalt8.obb");

	MyFileStream* s = ofs.createStream("z:\\Projects\\A8\\gui\\career_menu.bgin");
	delete s;

//	getch();
}
