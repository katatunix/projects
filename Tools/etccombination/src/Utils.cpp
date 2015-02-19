#include "Utils.h"

#include <stdio.h>
#include <windows.h>

std::string Utils::convert(const std::wstring& ws)
{
	std::string result;
	for (const char x : ws)
		result += x;
	return result;
}

std::wstring Utils::getPathOfExe()
{
	// Get filename with full path for current process EXE
	wchar_t filename[MAX_PATH];
	DWORD result = GetModuleFileName(
		nullptr,	// retrieve path of current process .EXE
		filename,
		_countof(filename)
	);

	if (result == 0)
	{
		// Error
	}

	return filename;
}

std::string Utils::runDOSCommand(const char* cmd)
{
	std::string result = "";

	FILE* pipe = _popen(cmd, "r");
	if (!pipe)
	{
		return "ERROR";
	}
	const int max = 128;
	char buffer[max];
	
	while (!feof(pipe))
	{
		if (fgets(buffer, max, pipe) != NULL)
			result += buffer;
	}
	_pclose(pipe);
	return result;
}

std::string Utils::runDOSCommand(const std::string& cmd)
{
	return runDOSCommand(cmd.c_str());
}

std::string Utils::exec(const char* cmd, const char* workingDir)
{
	std::string result = "";

	STARTUPINFO si;
	PROCESS_INFORMATION pi;

	ZeroMemory( &si, sizeof(si) );
	si.cb = sizeof(si);
	ZeroMemory( &pi, sizeof(pi) );

	int len = strlen(cmd);
	wchar_t* wCmd = new wchar_t[len + 1];
	mbstowcs(wCmd, cmd, len);
	wCmd[len] = 0;

	len = workingDir ? strlen(workingDir) : 0;
	wchar_t* wWorkingDir = NULL;
	if (len)
	{
		wWorkingDir = new wchar_t[len + 1];
		mbstowcs(wWorkingDir, workingDir, len);
		wWorkingDir[len] = 0;
	}

	// Start the child process. 
	if ( !CreateProcess( NULL,   // No module name (use command line)
		wCmd,			// Command line
		NULL,		   // Process handle not inheritable
		NULL,		   // Thread handle not inheritable
		FALSE,		  // Set handle inheritance to FALSE
		0,			  // No creation flags
		NULL,		   // Use parent's environment block
		wWorkingDir,	 // Use parent's starting directory 
		&si,			// Pointer to STARTUPINFO structure
		&pi )		   // Pointer to PROCESS_INFORMATION structure
	)
	{
		printf( "CreateProcess failed (%d).\n", GetLastError() );
		result = "failed\n";
	}
	else
	{
		// Wait until child process exits.
		WaitForSingleObject( pi.hProcess, INFINITE );

		// Close process and thread handles. 
		CloseHandle( pi.hProcess );
		CloseHandle( pi.hThread );

		result = "successful\n";
	}

	delete [] wCmd;
	if (wWorkingDir) delete [] wWorkingDir;

	return result;
}

std::string Utils::exec(const std::string& cmd, const std::string workingDir)
{
	return Utils::exec(cmd.c_str(), workingDir.c_str());
}

std::string Utils::getCurDir()
{
	std::wstring exePathTmp = getPathOfExe();
	std::string exePath = convert(exePathTmp);

	int last = exePath.find_last_of('\\');
	return exePath.substr(0, last);
}

std::string Utils::getBaseName(const std::string& filePath)
{
	int slash1 = filePath.find_last_of('\\');
	if (slash1 == std::string::npos) slash1 = -1;
	int slash2 = filePath.find_last_of('/');
	if (slash2 == std::string::npos) slash2 = -1;

	int slash = slash1 > slash2 ? slash1 : slash2;

	std::string fileName = slash == -1 ? filePath : filePath.substr(slash + 1);

	int dot = fileName.find_last_of('.');
	if (dot == std::string::npos)
	{
		return fileName;
	}
	return fileName.substr(0, dot);
}

std::string Utils::getTempPath()
{
	wchar_t buf [MAX_PATH];
	GetTempPath(MAX_PATH, buf);

	std::wstring tmp = buf;
	return convert(tmp);
}

int Utils::getFileSize(FILE* f)
{
	fseek(f, 0, SEEK_END);
	return ftell(f);
}
