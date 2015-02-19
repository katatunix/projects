#include <gata/filesystem/FileSystem.h>

namespace gata {
namespace filesystem {

IFileSystem* FileSystem::provideIFileSystem()
{
	return this;
}

FileSystem::FileSystem() : m_count(0), m_currentDir(".")
{

}

FileSystem::~FileSystem()
{
	for (MyMap::iterator it = m_files.begin(); it != m_files.end(); ++it)
	{
		fclose(it->second.handle);
	}
}

int FileSystem::open(const char* path)
{
	std::string realPath = std::string(path);
	
	if (!isAbsolutePath(path))
	{
		realPath = m_currentDir + "/" + path;
	}

	FILE* f = fopen(realPath.c_str(), "rb");
	if (!f) return 0;
	
	MyFile myFile;
	myFile.handle = f;
	fseek(f, 0, SEEK_END);
	myFile.size = ftell(f);
	rewind(f);

	m_count++;
	m_files[m_count] = myFile;
	return m_count;
}

void FileSystem::close(int file)
{
	MyMap::iterator it = m_files.find(file);
	if (it == m_files.end()) return;
	fclose(it->second.handle);
	m_files.erase(it);
}

int FileSystem::size(int file)
{
	MyMap::iterator it = m_files.find(file);
	if (it == m_files.end()) return -1;
	return it->second.size;
}

int FileSystem::tell(int file)
{
	MyMap::iterator it = m_files.find(file);
	if (it == m_files.end()) return -1;
	return ftell(it->second.handle);
}

void FileSystem::seekBeg(int file, int offset)
{
	MyMap::iterator it = m_files.find(file);
	if (it == m_files.end()) return;
	fseek(it->second.handle, offset, SEEK_SET);
}

void FileSystem::seekCur(int file, int offset)
{
	MyMap::iterator it = m_files.find(file);
	if (it == m_files.end()) return;
	fseek(it->second.handle, offset, SEEK_CUR);
}

void FileSystem::seekEnd(int file, int offset)
{
	MyMap::iterator it = m_files.find(file);
	if (it == m_files.end()) return;
	fseek(it->second.handle, offset, SEEK_END);
}

int FileSystem::read(int file, int bytes, void* buf)
{
	MyMap::iterator it = m_files.find(file);
	if (it == m_files.end()) return 0;

	return fread(buf, 1, bytes, it->second.handle);
}

void FileSystem::setCurrentDir(const char* path)
{
	m_currentDir = std::string(path);
}

bool FileSystem::isAbsolutePath(const char* path)
{
	return path && (path[0] == '/' || (path[0] && path[1] && path[1] == ':'));
}

}
}
