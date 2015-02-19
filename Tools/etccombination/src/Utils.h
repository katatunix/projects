#pragma once

#include <string>

class Utils
{
public:
	static std::string getCurDir();
	static std::string convert(const std::wstring& ws);

	static std::string runDOSCommand(const char* cmd);
	static std::string runDOSCommand(const std::string& cmd);

	static std::string exec(const char* cmd, const char* workingDir = NULL);
	static std::string exec(const std::string& cmd, const std::string workingDir = "");

	static std::wstring getPathOfExe();

	static std::string getBaseName(const std::string& filePath);

	static std::string getTempPath();

	static int getFileSize(FILE* f);
	
};
