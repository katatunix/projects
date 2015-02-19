using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;

namespace PLog
{
	class Utils
	{
		private static String SAVE_FILE = Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData) + "\\plog.sav";
		private static String EXCEPTION_FILE = Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData) + "\\plog_exception.sav";

		public static void SaveData(List<CFilter> filters)
		{
			try
			{
				StreamWriter sw = new StreamWriter(SAVE_FILE);
				for (int i = 0; i < filters.Count; i++)
				{
					sw.WriteLine(filters[i].getName());
					sw.WriteLine(filters[i].getTag());
					sw.WriteLine(filters[i].getPid());
				}
				sw.Close();
			}
			catch (Exception)
			{
			}
		}

		public static List<CFilter> LoadData()
		{
			List<CFilter> filters = new List<CFilter>();
			try
			{
				StreamReader sr = new StreamReader(SAVE_FILE);
				while (true)
				{
					String name = sr.ReadLine();
					if (name == null) break;
					if (name == "") name = "TAG";

					String tag = sr.ReadLine();
					if (tag == null) break;

					String p = sr.ReadLine();
					if (p == null) break;

					int pid = -1;
					try
					{
						pid = int.Parse(p);
					}
					catch (Exception)
					{
						break;
					}

					filters.Add(new CFilter(name, tag, pid));
				}
				sr.Close();
			}
			catch (Exception)
			{
			}
			return filters;
		}

		public static void saveException(String str)
		{
			try
			{
				StreamWriter sw = new StreamWriter(EXCEPTION_FILE);
				sw.Write(str);
				sw.Close();
			}
			catch (Exception)
			{
			}
		}

		public static String loadException()
		{
			try
			{
				StreamReader sr = new StreamReader(EXCEPTION_FILE);
				String str = sr.ReadToEnd();
				sr.Close();
				return str;
			}
			catch (Exception)
			{
				return "";
			}
		}

	}
}
