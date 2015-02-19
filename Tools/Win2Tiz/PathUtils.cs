using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;

namespace Win2Tiz
{
	class PathUtils
	{
		public static String combinePath(String pathFolder, String relPathFile)
		{
			// Make sure
			// pathFolder is a path of a folder, not end with \
			// relPath is a relative path of a file

			String[] p = relPathFile.Split('\\');
			int count = p.Length;

			String result = pathFolder;

			for (int i = 0; i < count; i++)
			{
				if (p[i] == "..")
				{
					int j = result.LastIndexOf('\\');
					if (j == -1) result += "\\..";
					else
					{
						result = result.Substring(0, j);
					}
				}
				else if (p[i] == ".")
				{
				}
				else if (p[i].Trim() != "")
				{
					result += "\\" + p[i].Trim();
				}
			}

			return result;
		}

		// Start from path1, how do we go to path2?
		public static string getRelativePath(string path2, string path1)
		{
			String[] p1 = path1.Split('\\');
			String[] p2 = path2.Split('\\');

			if (p1[0] != p2[0]) return path2;

			int count = Math.Min(p1.Length, p2.Length);
			int i;
			for (i = 1; i < count; i++)
			{
				if (p1[i] != p2[i]) break;
			}

			String result = "";
			for (int j = p2.Length; j >= i; j--)
			{
				result += "..\\";
			}

			bool first = true;

			for (int j = i; j < p1.Length; j++)
			{
				if (first)
				{
					result += p1[j];
					first = false;
				}
				else
				{
					result += "\\" + p1[j];
				}
			}

			return result;
		}

		public static string removeEndSlashes(string t)
		{
			while (t.EndsWith("\\") || t.EndsWith("/"))
			{
				t = t.Substring(0, t.Length - 1);
			}
			return t;
		}

		public static List<string> getDependFilesList(string depFilePath)
		{
			try
			{
				List<string> list = new List<string>();
				StreamReader reader = new StreamReader(depFilePath);
				string line;
				while ((line = reader.ReadLine()) != null)
				{
					if (!line.EndsWith(":")) continue;
					list.Add(line.Substring(0, line.Length - 1));
				}
				reader.Close();

				return list;
			}
			catch (Exception)
			{
				return null;
			}
		}

		//public static bool newer(string path1, string path2)
		//{
			
		//}


	}
}
