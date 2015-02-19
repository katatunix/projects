using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using System.Diagnostics;

namespace PLog
{
	class CConfig
	{
		public static String m_NDK = "";
		public static String m_Python = "";
		public static String m_soLib = "";

		public static String LOG_FILE = Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData) + "\\plog_log.txt";
		public static String PARSE_STACK_PY_FILE = Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData) + "\\parse_stack.py";
		public static String CONFIG_FILE = Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData) + "\\plog_config.sav";

		public static List<String> m_sExceptionTags = new List<String>();
		public static List<int> m_sExceptionPids = new List<int>();

		public static void save()
		{
			StreamWriter sw = new StreamWriter(CONFIG_FILE);
			sw.WriteLine(m_NDK);
			sw.WriteLine(m_Python);
			sw.WriteLine(m_soLib);
			sw.Close();
		}

		public static void load()
		{
			try
			{
				StreamReader sr = new StreamReader(CONFIG_FILE);
				m_NDK = sr.ReadLine();
				m_Python = sr.ReadLine();
				m_soLib = sr.ReadLine();
				sr.Close();
			}
			catch (Exception)
			{
			}

			parseExceptionList(Utils.loadException());
		}

		public static void parseExceptionList(String excep)
		{
			m_sExceptionTags.Clear();
			m_sExceptionPids.Clear();
			
			String[] keys = { "\n" };
			String[] arr = excep.Split(keys, StringSplitOptions.RemoveEmptyEntries);
			foreach (String ex2 in arr)
			{
				String ex = ex2.Trim();
				if (ex.StartsWith("//")) continue;

				String tag = "";
				int pid = -1;
				int i = ex.IndexOf(",");
				if (i == -1)
				{
					tag = ex;
				}
				else
				{
					tag = ex.Substring(0, i).Trim();
					try
					{
						pid = int.Parse(ex.Substring(i + 1));
					}
					catch (Exception)
					{
						pid = -1;
					}
				}
				
				m_sExceptionTags.Add(tag);
				m_sExceptionPids.Add(pid);
			}
		}

		public static bool isException(String tag, int pid)
		{
			for (int i = 0; i < m_sExceptionTags.Count; i++)
			{
				String t = m_sExceptionTags[i];
				int p = m_sExceptionPids[i];
				if ( (t == "" || t.Equals(tag)) && (p == -1 || p == pid) )
				{
					return true;
				}
			}
			return false;
		}

		public static String getExceptionString()
		{
			String s = "";
			for (int i = 0; i < m_sExceptionTags.Count; i++)
			{
				String t = m_sExceptionTags[i];
				int p = m_sExceptionPids[i];
				s += t;
				if (p > -1)
				{
					s += ", " + p;
				}
				s += "\n";
			}
			return s;
		}
	
	}
}
