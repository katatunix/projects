#include "ObbFileSystem.h"
#include "Utils.h"

namespace obbfilesystem
{

ObbFileSystem::ObbFileSystem()
{
}

ObbFileSystem::~ObbFileSystem()
{
}

// must end with "/" or "\\"
void ObbFileSystem::setBaseFolderPath(const char* absPath)
{
	m_baseFolderAbsPath = absPath;
}

void ObbFileSystem::setObbFilePath(const char* absPath, s32 signature/* = 0x02014b50*/)
{
	MyFileStream s;
	s.open(absPath, 0, 0);
	if (!s.isOpen())
	{
		return;
	}

	MyMap folders;
	Entry rootEntry;

	size_t central_dir_offset = findCentralDirectoryOffset(s);
	if (central_dir_offset == static_cast<size_t>(-1))
	{
		return;
	}

	//
	m_obbFileAbsPath = absPath;
	m_entries.clear();
	rootEntry.index = 0;
	m_entries.push_back(rootEntry);

	size_t streamSize = s.size();
	s.seekBeg(central_dir_offset);

	char bufferPath[1024];

	while (s.tell() < streamSize)
	{
		ZipFileDescriptor fd;
		DeobfuscatorHeader h;

		s >> h.signature;
		s >> h.version_made_by;
		s >> h.version_needed_to_extract;
		s >> fd.general_bit_flag;
		s >> fd.compression_method;
		s >> fd.last_mod_file_time;
		s >> fd.last_mod_file_date;
		s >> fd.crc_32;
		s >> fd.compressed_size;
		s >> fd.uncompressed_size;
		s >> h.filename_length;
		s >> h.extra_field_length;
		s >> h.file_comment_length;
		s >> h.disk_number_start;
		s >> h.internal_file_attributes;
		s >> h.external_file_attributes;
		s >> h.relative_offset_of_local_header;
		
		// Sanity checks
		if (h.signature != signature)
		{
			break;
		}

		fd.file_offset = h.relative_offset_of_local_header;
		fd.local_header_read = false;

		s.read(bufferPath, h.filename_length);
		bufferPath[h.filename_length] = 0;

		addEntry(bufferPath, folders, fd); // ===

		s.seekCur(h.extra_field_length);
		s.seekCur(h.file_comment_length);
	}
}

MyFileStream* ObbFileSystem::createStream(const char* path)
{
	const char* finalPath = NULL;
	u32 start = 0, size = 0;
	FILE* f = fopen(path, "rb");

	if (f)
	{
		fclose(f);
		finalPath = path;
	}
	else if (!m_baseFolderAbsPath.empty() && !m_obbFileAbsPath.empty()
		&& isStartWith(path, m_baseFolderAbsPath.c_str()))
	{
		const char* pathInObb = path + m_baseFolderAbsPath.length();
		s32 entryIndex = findEntry(pathInObb);
		if (entryIndex == -1) return NULL;

		Entry& entry = m_entries[entryIndex];
		ZipFileDescriptor& zipDesc = entry.zipDesc;
		if (!zipDesc.local_header_read)
		{
			MyFileStream stream;
			stream.open(m_obbFileAbsPath.c_str(), 0, 0);
			stream.seekBeg(zipDesc.file_offset);

			//read local header !!!!
			ZipFileDescriptor head;
			u32 signature;
			u16 version_needed_to_extract;
			u16 filename_length;
			u16 extra_field_length;

			stream >> signature;
			stream >> version_needed_to_extract;
			stream >> head.general_bit_flag;
			stream >> head.compression_method;
			stream >> head.last_mod_file_date;
			stream >> head.last_mod_file_time;
			stream >> head.crc_32;
			stream >> head.compressed_size;
			stream >> head.uncompressed_size;
			stream >> filename_length;
			stream >> extra_field_length;

			zipDesc.file_offset += 30 + filename_length + extra_field_length;
			zipDesc.compressed_size = head.compressed_size;
			zipDesc.uncompressed_size = head.uncompressed_size;

			zipDesc.local_header_read = true;
		}

		finalPath = m_obbFileAbsPath.c_str();
		start = zipDesc.file_offset;
		size = zipDesc.compressed_size;
	}
	else
	{
		return NULL;
	}

	MyFileStream* ret = new MyFileStream();
	ret->open(finalPath, start, size);
	return ret;
}

/**
	Make sure:
		+ path is not empty
**/
void ObbFileSystem::addEntry(const char* p, MyMap& folders, const ZipFileDescriptor& zipDesc)
{
	std::string path = p;
	char lastChar = path[path.length() - 1];
	bool isFolder = false;
	if (lastChar == '\\' || lastChar == '/')
	{
		isFolder = true;
		// Cut off the last char
		path = path.substr(0, path.length() - 1);
	}

	std::string parentPath;
	std::string name;
	size_t off = path.find_last_of("\\/");
	if (off == std::string::npos)
	{
		name = path;
	}
	else
	{
		parentPath = path.substr(0, off);
		name = path.substr(off + 1);
	}

	Entry entry;
	entry.zipDesc = zipDesc;

	entry.index = m_entries.size();
	entry.name = name;
	entry.isFolder = isFolder;

	entry.parentIndex = 0;
	if (!parentPath.empty())
	{
		MyMap::iterator ite = folders.find(parentPath);
		if (ite == folders.end())
		{
			return;
		}
		entry.parentIndex = ite->second;
	}

	m_entries[entry.parentIndex].childIndices.push_back(entry.index);

	m_entries.push_back(entry);

	if (isFolder)
	{
		folders[path] = entry.index;
	}
}

size_t ObbFileSystem::findCentralDirectoryOffset(MyFileStream& s)
{
	s.seekBeg(s.size() - 22);

	u32 signature;
	s >> signature;
	if (signature != 0x06054b50)
	{
		return static_cast<size_t>(-1);
	}

	s.seekCur(2); //number of this disk
	s.seekCur(2); //number of this disk with the start of the central directory
	s.seekCur(2); //total number of entries in the central dir on this disk
	s.seekCur(2); //total number of entries in the central dir
	s.seekCur(4); //size of the central directory
	u32 offset;
	s >> offset;

	if (offset >= s.tell())
	{
		return static_cast<size_t>(-1);
	}

	return offset;
}


s32 ObbFileSystem::findEntry(const char* path)
{
	int len = strlen(path);
	if (len == 0)
	{
		return 0; // root
	}
	char firstChar = path[0];
	if (len == 1 && (firstChar == '.' || firstChar == '/' || firstChar == '\\'))
	{
		return 0; // root
	}

	std::vector<std::string> tokens = splitString(path, "\\/", true);
	if (tokens.empty())
	{
		return -1;
	}

	const Entry* parent = &m_entries[0];

	for (u32 i = 0; i < tokens.size(); i++)
	{
		const std::string& name = tokens[i];
		bool found = false;
		for (u32 c = 0; c < parent->childIndices.size(); c++)
		{
			const Entry* child = &m_entries[parent->childIndices[c]];
			if (child->name == name)
			{
				found = true;
				if (i == tokens.size() - 1)
				{
					return child->index;
				}
				parent = child;
				break;
			}
		}
		if (!found)
		{
			return -1;
		}
	}

	return -1;
}

} // namespace
