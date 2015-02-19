#include <stdio.h>
#include <conio.h>

#include <string>

#include "Utils.h"
#include "ktx.h"
#include "mmap.h"

#include "lz4/lz4.h"

#define MAX(x, y) (((x) > (y)) ? (x) : (y))

int main(int argc, char* args[])
{
	if (argc < 3)
	{
		printf("Usage: etccombination <inputFile> <outputDir> [-m] [-lz4]\n");
		return 1;
	}

	const std::string inputFile = args[1];
	const std::string outputDir = args[2];
	std::string mipmaps = "";
	bool useLZ4 = false;
	for (int i = 3; i < argc; i++)
	{
		if (strcmp(args[i], "-m") == 0)
		{
			mipmaps = "-mipmaps";
		}
		else if (strcmp(args[i], "-lz4") == 0)
		{
			useLZ4 = true;
		}
	}

	const std::string curDir = Utils::getCurDir();

	const std::string etcpack = curDir + "\\etcpack.exe";

	const std::string _tmp_etc1 = "_tmp_etc1";
	const std::string _tmp_etc2 = "_tmp_etc2";
	const std::string _ktx = ".ktx";
	const std::string _kty = ".kty";

	const std::string tempPath = curDir + "\\";// Utils::getTempPath(); // with '\\' at the end

	const std::string outputDirTmp1 = tempPath + _tmp_etc1;
	const std::string outputDirTmp2 = tempPath + _tmp_etc2;

	printf("Compress ETC1...\n");
	std::string
	log = Utils::exec(etcpack + " " + inputFile + " " + outputDirTmp1 + " " + mipmaps + " -ktx -c etc1 -f RGB -s fast", curDir);
	printf("%s", log.c_str());

	printf("Compress ETC2...\n");
	log = Utils::exec(etcpack + " " + inputFile + " " + outputDirTmp2 + " " + mipmaps + " -ktx -c etc2 -f RGB -s fast", curDir);
	printf("%s", log.c_str());

	printf("Processing...\n");

	const std::string baseInputFileName = Utils::getBaseName(inputFile);

	const std::string etc1FilePath = outputDirTmp1 + "\\" + baseInputFileName + _ktx;
	const std::string etc2FilePath = outputDirTmp2 + "\\" + baseInputFileName + _ktx;
	const std::string outFilePath = outputDir + "\\" + baseInputFileName + _kty;

	FILE* f1 = fopen(etc1FilePath.c_str(), "rb");
	int len = Utils::getFileSize(f1);
	rewind(f1);
	auto ptr1 = (char*)mmap( nullptr, len, PROT_READ, MAP_SHARED, _fileno( f1 ), 0 );
	auto _ptr1 = ptr1;
	
	FILE* f2 = fopen(etc2FilePath.c_str(), "rb");
	len = Utils::getFileSize(f2);
	rewind(f2);
	auto ptr2 = (char*)mmap( nullptr, len, PROT_READ, MAP_SHARED, _fileno( f2 ), 0 );
	auto _ptr2 = ptr2;

	FILE* f3 = fopen(outFilePath.c_str(), "wb");

	//
	KTX_header header;
	memcpy(&header, ptr1, KTX_HEADER_SIZE);
	
	header.identifier[3] = 'Y'; // 'X' -> 'Y'
	khronos_uint32_t original_bytesOfKeyValueData = header.bytesOfKeyValueData;
	header.bytesOfKeyValueData = useLZ4 ? 1 : 0;

	fwrite(&header, KTX_HEADER_SIZE, 1, f3);
	if (header.bytesOfKeyValueData == 1)
	{
		int v = 1;
		fwrite(&v, 1, 1, f3);
	}
	
	ptr1 += KTX_HEADER_SIZE + original_bytesOfKeyValueData;
	ptr2 += KTX_HEADER_SIZE + original_bytesOfKeyValueData;

	//
	const int k_size = sizeof(khronos_uint32_t);
	const int k_block_size = 8; // bytes

	char* pMerged = NULL;
	char* pMergedCompressed = NULL;

	for (khronos_uint32_t level = 0; level < header.numberOfMipmapLevels; level++)
	{
		printf("  Level: %d\n", level);

		int pixelWidth  = MAX(1, header.pixelWidth  >> level);
		int pixelHeight = MAX(1, header.pixelHeight >> level);

		khronos_uint32_t faceLodSize = *( (khronos_uint32_t*)ptr1 );
		ptr1 += k_size;
		ptr2 += k_size;

		if (header.endianness == KTX_ENDIAN_REF_REV)
		{
			_ktxSwapEndian32(&faceLodSize, 1);
		}
		khronos_uint32_t faceLodSizeRounded = (faceLodSize + 3) & ~(khronos_uint32_t)3;

		if (!pMerged)
		{
			pMerged = (char*)malloc(faceLodSize * 2);
		}
		if (!pMergedCompressed)
		{
			pMergedCompressed = (char*)malloc(faceLodSize * 2);
		}

		for (khronos_uint32_t face = 0; face < header.numberOfFaces; face++)
		{
			printf("    Face: %d\n", face);

			if (pixelWidth >= 4 && pixelHeight >= 4)
			{
				char* pOut = pMerged;
				char* pEnd = ptr1 + faceLodSize;
				while (ptr1 < pEnd)
				{
					if (memcmp(ptr1, ptr2, k_block_size) == 0) // this is ETC1 (=> must use ETC1)
					{
						memcpy(pOut, ptr1, k_block_size);
						pOut += k_block_size;
					}
					else // store both ETC1 and ETC2 blocks
					{
						// note: store ETC2 first
						memcpy(pOut, ptr2, k_block_size);
						pOut += k_block_size;
						memcpy(pOut, ptr1, k_block_size);
						pOut += k_block_size;
					}
					ptr1 += k_block_size;
					ptr2 += k_block_size;
				}

				// now write to f3
				khronos_uint32_t bytes = pOut - pMerged;

				if (useLZ4)
				{
					khronos_uint32_t compressedSize = (khronos_uint32_t)LZ4_compress(pMerged, pMergedCompressed, bytes);
					fwrite(&compressedSize, k_size, 1, f3);
					fwrite(pMergedCompressed, compressedSize, 1, f3);
				}
				else
				{
					fwrite(&bytes, k_size, 1, f3);
					fwrite(pMerged, bytes, 1, f3);
				}
			}
			else // the width, height is too small => use ETC1
			{
				fwrite(&faceLodSize, k_size, 1, f3);
				fwrite(ptr1, faceLodSize, 1, f3);
				ptr1 += faceLodSize;
				ptr2 += faceLodSize;
			}

			ptr1 += faceLodSizeRounded - faceLodSize;
			ptr2 += faceLodSizeRounded - faceLodSize;
		}
	}

	if (pMerged)
	{
		free(pMerged);
	}
	if (pMergedCompressed)
	{
		free(pMergedCompressed);
	}

	
	khronos_uint32_t originalBytes = ptr1 - _ptr1;
	khronos_uint32_t mergedBytes = ftell(f3);

	munmap(_ptr2, 0);
	munmap(_ptr1, 0);

	fclose(f3);
	fclose(f2);
	fclose(f1);

	/*log = Utils::runDOSCommand("rd /s /q " + outputDirTmp1);
	log = Utils::runDOSCommand("rd /s /q " + outputDirTmp2);*/
	remove(etc1FilePath.c_str());
	remove(etc2FilePath.c_str());

	printf("originalBytes: %d, mergedBytes: %d, ratio: %.2f\n",
		originalBytes, mergedBytes, (float)mergedBytes / (float)originalBytes);

	printf("DONE for texture: %s\n", baseInputFileName.c_str());
	//_getch();
	return 0;
}
