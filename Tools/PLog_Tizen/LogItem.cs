using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace PLog
{
	class LogItem
	{
		public const int INFO = 0;
		public const int DEBUG = 1;
		public const int WARNING = 2;
		public const int ERROR = 3;
		
		private String m_time;
		private String m_tag;
		private int m_type;
		private int m_pid;
		private String m_content;

		public LogItem(String logLineStr)
		{
			if (logLineStr == null || logLineStr.Trim().Length == 0) return;
			logLineStr = logLineStr.Trim();

			DateTime dt = DateTime.Now;
            m_time = dt.Year + "/" + dt.Month + "/" + dt.Day + " " + dt.Hour + ":" + dt.Minute + ":" + dt.Second
                + "." + dt.Millisecond;

            m_type = INFO;
            m_tag = "";
            m_pid = -1;

            if (logLineStr.Length < 2 || logLineStr[1] == '/')
            {
                parse1(logLineStr);
            }
            else
            {
                parse2(logLineStr);
            }

			
		}

        private void parse1(String logLineStr)
        {
            m_content = "";
            if (logLineStr.StartsWith("W/"))
            {
                m_type = WARNING;
                m_content += "W/";
            }
            else if (logLineStr.StartsWith("D/"))
            {
                m_type = DEBUG;
                m_content += "D/";
            }
            else if (logLineStr.StartsWith("E/"))
            {
                m_type = ERROR;
                m_content += "E/";
            }
            else if (logLineStr.StartsWith("I/"))
            {
                m_type = INFO;
                m_content += "I/";
            }

            int i = logLineStr.IndexOf('/');
            if (i > -1)
            {
                int j = logLineStr.IndexOf('(');
                if (j > -1)
                {
                    m_tag = logLineStr.Substring(i + 1, j - i - 1).Trim();
                    m_content += m_tag + " ";
                    int k = logLineStr.IndexOf(')', j);
                    if (k > -1)
                    {
                        try
                        {
                            m_pid = int.Parse(logLineStr.Substring(j + 1, k - j - 1));
                        }
                        catch (Exception)
                        {
                            m_pid = -1;
                        }
                        m_content += "(" + m_pid + ") " + removeFuncLineNum( logLineStr.Substring(k + 1) );
                    }
                }
            }
        }

        private void parse2(String logLineStr)
        {
            m_content = logLineStr;

            int index = logLineStr.IndexOf(':', 20);
            if (index == -1) return;
            
            String str = logLineStr.Substring(0, index);
            String[] keys = { " ", "\t" };
            String[] arr = str.Split(keys, StringSplitOptions.RemoveEmptyEntries);
            
            if (arr.Length < 5) return;

            try
            {
                m_pid = int.Parse(arr[2]);
            }
            catch (Exception)
            {
                m_pid = -1;
            }

            if (arr[4] == "W")
            {
                m_type = WARNING;
            }
            else if (arr[4] == "D")
            {
                m_type = DEBUG;
            }
            else if (arr[4] == "E")
            {
                m_type = ERROR;
            }

            if (arr.Length >= 6)
            {
                m_tag = arr[5];
            }

            String pidStr = m_pid > -1 ? m_pid.ToString() : "";

            //m_content = arr[4] + "/" + m_tag + "(" + pidStr + "):" + logLineStr.Substring(index + 1);
            m_content = arr[4] + "/" + m_tag + "(" + pidStr + "):" + removeFuncLineNum( logLineStr.Substring(index + 1) );
        }

		public String toString()
		{
            // Nghia
			return "[" + m_time + "] " + m_content;
		}

        private string removeFuncLineNum(string s)
        {
            int index = s.IndexOf('>');
            return s.Substring(index + 1).Trim();
        }

		public String getTime() { return m_time; }
		public String getTag() { return m_tag; }
		public int getType() { return m_type; }
		public int getPid() { return m_pid; }
		public String getContent() { return m_content; }
	}
}
