#ifndef _OFS_OBB_FILE_SYSTEM_H_
#define _OFS_OBB_FILE_SYSTEM_H_

#include <string>
#include <vector>
#include <map>

#include "MyFileStream.h"

namespace obbfilesystem
{

class ObbFileSystem
{
public:
	static ObbFileSystem* getInstance()
	{
		static ObbFileSystem instance;
		return &instance;
	}

private:
	ObbFileSystem();
	~ObbFileSystem();

public:
	void setBaseFolderPath(const char* absPath); // must end with "/"

	void setObbFilePath(const char* absPath, s32 signature = 0x02014b50);

	MyFileStream* createStream(const char* path);
	
private:
	typedef std::map<std::string, u32> MyMap;

	struct ZipFileDescriptor
	{
		u32 file_offset; // position of compressed data in file
		u16 general_bit_flag;
		u16 compression_method;
		u16 last_mod_file_date;
		u16 last_mod_file_time;
		u32 crc_32;
		u32 compressed_size;
		u32 uncompressed_size;

		bool local_header_read;
	};

	struct DeobfuscatorHeader
	{
		u32 signature;
		u16 version_made_by;
		u16 version_needed_to_extract;
		u16 filename_length;
		u16 extra_field_length;
		u16 file_comment_length;
		u16 disk_number_start;
		u16 internal_file_attributes;
		u32 external_file_attributes;
		u32 relative_offset_of_local_header;
	};

	struct Entry
	{
		// index in the m_entries
		u32 index;
		u32 parentIndex;
		std::vector<u32> childIndices;

		std::string name; // file/folder name, not path
		bool isFolder;
		
		ZipFileDescriptor zipDesc;
	};

	//=================================================================================

	void addEntry(const char* path, MyMap& folders, const ZipFileDescriptor& zipDesc);
	size_t findCentralDirectoryOffset(MyFileStream& s);
	s32 findEntry(const char* path);

	//=================================================================================

	std::string m_baseFolderAbsPath;
	std::string m_obbFileAbsPath;

	std::vector<Entry> m_entries;
};

}
#endif
