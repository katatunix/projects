using System;
using System.Collections.Generic;
using System.Text;
using System.IO;

namespace win2tiz.utils
{
	/// <summary>
	/// Depend on:
	/// CConsole
	/// </summary>
	class CUtils
	{
		public static string combinePath(string path1, string path2)
		{
			return Path.GetFullPath(Path.Combine(path1, path2));
		}

		public static string combinePathSimple(string path1, string path2)
		{
			return Path.Combine(path1, path2);
		}

		public static string getFolderPath(string filePath)
		{
			return Path.GetDirectoryName(filePath);
		}

		public static string getFullPath(string path)
		{
			return Path.GetFullPath(path);
		}

		public static string getPathRoot(string path)
		{
			return Path.GetPathRoot(path);
		}

		public static bool isCppExt(string ext)
		{
			return	ext == ".cpp"	||
					ext == ".cc"	||
					ext == ".cxx"	||
					ext == ".C";
		}

		public static bool isAssemblyExt(string ext)
		{
			return ext == ".s" || ext == ".S";
		}

		public static string[] getFilesWithWildcard(string path)
		{
			int i = path.LastIndexOfAny(s_kSlashes);
			if (i == -1) return new string[] { path };

			string folderPath = path.Substring(0, i);
			string pattern = path.Substring(i + 1);

			return Directory.GetFiles(folderPath, pattern, SearchOption.TopDirectoryOnly);
		}

		public static string getRelativePath(string filespec, string folder)
		{
			Uri pathUri = new Uri(filespec);
			// Folders must end in a slash
			if (!folder.EndsWith(Path.DirectorySeparatorChar.ToString()))
			{
				folder += Path.DirectorySeparatorChar;
			}
			Uri folderUri = new Uri(folder);
			return Uri.UnescapeDataString(
				folderUri.MakeRelativeUri(pathUri).ToString().Replace('/', Path.DirectorySeparatorChar)
			);
		}

		public static bool isSamePath(string path1, string path2)
		{
			path1 = Path.GetFullPath(path1);
			while (path1.EndsWith("\\") || path1.EndsWith("/")) path1 = path1.Substring(0, path1.Length - 1);

			path2 = Path.GetFullPath(path2);
			while (path2.EndsWith("\\") || path2.EndsWith("/")) path2 = path2.Substring(0, path2.Length - 1);

			return path1.ToLower() == path2.ToLower();
		}

		public static bool checkPathExistInList(string path, List<string> list)
		{
			for (int i = 0; i < list.Count; i++)
			{
				if (isSamePath(list[i], path))
				{
					return true;
				}
			}
			return false;
		}

		public static bool checkPatternFile(string sourcePath, string pattern, string vcDir)
		{
			// *.c *.hpp
			if (pattern.StartsWith("*."))
			{
				string ext = pattern.Substring(1); // .c
				return ext == Path.GetExtension(sourcePath);
			}

			if (pattern.StartsWith(".") || Path.IsPathRooted(pattern))
			{
				string patternPath = combinePath(vcDir, pattern);

				if (string.IsNullOrEmpty(Path.GetExtension(patternPath)))
				{
					string sourcePathWithoutExt = Path.GetDirectoryName(sourcePath) + "\\" +
						Path.GetFileNameWithoutExtension(sourcePath);
					return isSamePath(sourcePathWithoutExt, patternPath);
				}
				return isSamePath(sourcePath, patternPath);
			}

			if (string.IsNullOrEmpty(Path.GetExtension(pattern)))
			{
				return pattern.ToLower() == Path.GetFileNameWithoutExtension(sourcePath).ToLower();
			}

			return pattern.ToLower() == Path.GetFileName(sourcePath).ToLower();
		}

		public static string makeGccDefinesString(string[] defines)
		{
			string res = "";
			foreach (string item in defines)
			{
				if (res.Length > 0) res += " ";
				res += s_kDefine + item;
			}
			return res;
		}

		public static string makeGccIncludePathsString(string[] includePaths)
		{
			string res = "";
			foreach (string item in includePaths)
			{
				if (res.Length > 0) res += " ";
				res += s_kIncludePath + item;
			}
			return res;
		}

		public static string makeGccLinkPathsString(string[] linkPaths)
		{
			string res = "";
			foreach (string item in linkPaths)
			{
				if (res.Length > 0) res += " ";
				res += s_kLinkPath + item;
			}
			return res;
		}

		public static string makeGccLinkLibsString(string[] linkLibs)
		{
			string res = "";
			foreach (string item in linkLibs)
			{
				if (res.Length > 0) res += " ";
				res += s_kLib + item;
			}
			return res;
		}

		public static string makeGccItemsString(string[] items)
		{
			string res = "";
			foreach (string item in items)
			{
				if (res.Length > 0) res += " ";
				res += item;
			}
			return res;
		}

		public static void gc()
		{
			GC.Collect();
			GC.WaitForPendingFinalizers();
		}

		public static void assert(bool b)
		{
			if (!b)
			{
				CConsole.writeError("Assert error!\n");
			}
		}

		public static void memcpy(byte[] dst, int dstOffset, byte[] src, int srcOffset, int len)
		{
			if (len <= 0) return;

			Buffer.BlockCopy(src, srcOffset, dst, dstOffset, len);
		}

		public static void memset(byte[] dst, int offset, int len, byte val)
		{
			for (int i = offset; i < offset + len; i++)
			{
				dst[i] = val;
			}
		}

		public static int setByte(int x, int offset, byte value)
		{
			x = ( ~(0xFF << (offset * 8)) ) & x;
			int tmp = (int)value;
			return ( tmp << (offset * 8) ) | x;
		}

		public static void int2bytes(int x, byte[] bytes, int offset = 0)
		{
			int mask = 0xFF;
			bytes[offset + 0] = (byte)(x & mask);
			bytes[offset + 1] = (byte)((x & (mask << 8)) >> 8);
			bytes[offset + 2] = (byte)((x & (mask << 16)) >> 16);
			bytes[offset + 3] = (byte)((x & (mask << 24)) >> 24);
		}

		public static void uint2bytes(uint x, byte[] bytes, int offset = 0)
		{
			int mask = 0xFF;
			bytes[offset + 0] = (byte)(x & mask);
			bytes[offset + 1] = (byte)((x & (mask << 8)) >> 8);
			bytes[offset + 2] = (byte)((x & (mask << 16)) >> 16);
			bytes[offset + 3] = (byte)((x & (mask << 24)) >> 24);
		}

		public static string getStringFromBytes(byte[] bytes)
		{
			return ASCIIEncoding.ASCII.GetString(bytes);
		}

		public static string getStringFromBytes(byte[] bytes, int offset, int count)
		{
			return ASCIIEncoding.ASCII.GetString(bytes, offset, count);
		}

		public static void getBytesFromString(string str, byte[] bytes, int offset = 0)
		{
			ASCIIEncoding.ASCII.GetBytes(str, 0, str.Length, bytes, offset);
		}
	
		public static void shakeOrder(int[] orders)
		{
			if (orders == null || orders.Length <= 0) return;

			//Random s_random = new Random();

			int len = orders.Length;
			bool[] cx = new bool[len];
			for (int i = 0; i < len; i++) cx[i] = true;

			int remain = len;
			for (int i = 0; i < len; i++)
			{
				int rand = s_random.Next(remain);
				for (int j = 0; j < len; j++)
				{
					if (cx[j])
					{
						rand--;
						if (rand < 0)
						{
							orders[i] = j;
							cx[j] = false;
							break;
						}
					}
				}
				remain--;
			}
		}

		//===============================================================================================
		//===============================================================================================
		private static Random s_random = new Random();
		private static readonly char[] s_kSlashes = { '\\', '/' };

		//===============================================================================================
		//===============================================================================================
		public static readonly string s_kGenDsym				= "-g";
		public static readonly string s_kMakeDependencies		= "-MD";
		public static readonly string s_kMakeDependenciesOnly	= "-M -E -MF";

		public static readonly string s_kIncludePath			= "-I";
		public static readonly string s_kDefine					= "-D";
		public static readonly string s_kLinkPath				= "-L";
		public static readonly string s_kLib					= "-l";
		public static readonly string s_kOutput					= "-o";
		public static readonly string s_kCompile				= "-c";

		public static readonly string s_kDebugPrefixMap			= "-fdebug-prefix-map=";
	}
}
