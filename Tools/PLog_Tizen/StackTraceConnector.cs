using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Diagnostics;
using System.IO;

namespace PLog
{
	class StackTraceConnector
	{
		public static String getStackTrace(String logTxt)
		{
			StreamWriter sw = new StreamWriter(CConfig.PARSE_STACK_PY_FILE);
			sw.Write( Properties.Settings.Default.parse_stack_py );
			sw.Close();

			sw = new StreamWriter(CConfig.LOG_FILE);
			sw.Write( logTxt );
			sw.Close();

			Process p = new Process();
			p.StartInfo.FileName = CConfig.m_Python + "\\python.exe";
			p.StartInfo.Arguments = CConfig.PARSE_STACK_PY_FILE + " " +
				CConfig.LOG_FILE + " " +
				CConfig.m_soLib + " " +
				CConfig.m_NDK + @"\toolchains\arm-linux-androideabi-4.4.3\prebuilt\windows\bin\arm-linux-androideabi-addr2line.exe";

			p.StartInfo.CreateNoWindow = true;
			p.StartInfo.UseShellExecute = false;
			p.StartInfo.RedirectStandardOutput = true;

			String s = "";
			try
			{
				p.Start();
				s = p.StandardOutput.ReadToEnd();
				p.WaitForExit();
			}
			catch (Exception)
			{
			}

			try { p.Kill(); p.Close(); }
			catch (Exception) { }

			return s;
		}
	}
}
