using System;
using System.Diagnostics;
using System.Threading;
using System.Management;
using System.IO;

using win2tiz.utils;

namespace win2tiz
{
	/// <summary>
	/// Depend on:
	/// TProcessResult
	/// CConsole
	/// CUtils
	/// </summary>
	class CProcessHelper
	{
		public static TProcessResult executeStatic(string cmd, string workingDir)
		{
			return new CProcessHelper().execute(cmd, workingDir);
		}

		private static void killProcessAndChildren(int pid, bool isKillRoot)
		{
			ManagementObjectSearcher searcher = new ManagementObjectSearcher(
				"Select * From Win32_Process Where ParentProcessID=" + pid);
			ManagementObjectCollection moc = searcher.Get();
			foreach (ManagementObject mo in moc)
			{
				killProcessAndChildren(Convert.ToInt32(mo["ProcessID"]), true);
			}

			if (isKillRoot)
			{
				try
				{
					Process proc = Process.GetProcessById(pid);

					CConsole.writeWarning(string.Format("Kill process [{0}] {1}\n", pid, proc.ProcessName));
					proc.Kill();
				}
				catch (Exception)
				{
					// Process already exited
				}
			}
		}

		//===================================================================================================
		//===================================================================================================

		public CProcessHelper()
		{
			reset();
		}

		public TProcessResult execute(string cmd, string workingDir)
		{
			int spaceIndex = cmd.IndexOf(" ");
			if (spaceIndex == -1)
			{
				return new TProcessResult(false, false, null, 0, "error: Invalid command!");
			}

			ProcessStartInfo psi = new ProcessStartInfo();
			psi.UseShellExecute = false;
			psi.RedirectStandardError = true;
			psi.RedirectStandardOutput = true;
			psi.RedirectStandardInput = true;
			psi.WindowStyle = ProcessWindowStyle.Hidden;
			psi.CreateNoWindow = true;
			psi.ErrorDialog = false;

			psi.FileName = cmd.Substring(0, spaceIndex);
			psi.Arguments = cmd.Substring(spaceIndex + 1);
			psi.WorkingDirectory = workingDir;

			m_cmd = cmd;
			m_workingDir = workingDir;

			try
			{
				using (m_process = Process.Start(psi))
				{
					using (ManualResetEvent mreOut = new ManualResetEvent(false), mreErr = new ManualResetEvent(false))
					{
						string output = "";

						m_process.OutputDataReceived += (o, e) => { if (e.Data == null) mreOut.Set(); else { output += e.Data + "\r\n"; } };
						m_process.BeginOutputReadLine();
						m_process.ErrorDataReceived += (o, e) => { if (e.Data == null) mreErr.Set(); else { output += e.Data + "\r\n"; } };
						m_process.BeginErrorReadLine();

						m_process.StandardInput.Close();

						m_process.WaitForExit(); // block

						mreOut.WaitOne();
						mreErr.WaitOne();

						int exitCode = m_process.ExitCode;

						m_process = null;
						return new TProcessResult(true, false, null, exitCode, output.Trim());
					}
				}
			}
			catch (Exception ex)
			{
				m_process = null;
				return new TProcessResult(false, false, null, 0, ex.Message + " [" + psi.FileName + "]");
			}
		}

		public void forceStop()
		{
			if (m_process != null)
			{
				killProcessAndChildren(m_process.Id, true);

				if (m_cmd != null)
				{
					string outFilePath = parseOutFilePath(m_cmd, m_workingDir);
					if (outFilePath != null)
					{
						try
						{
							CConsole.writeInfoLine("Delete: " + outFilePath);
							File.Delete(outFilePath);
						}
						catch (Exception)
						{
						}
					}
				}
			}
			reset();
		}

		private void reset()
		{
			m_process = null;
			m_cmd = null;
			m_workingDir = null;
		}

		//=======================================================================================================

		private static string parseOutFilePath(string cmd, string workingDir)
		{
			string[] p = cmd.Split(s_kSeparateChars, StringSplitOptions.RemoveEmptyEntries);
			for (int i = 0; i < p.Length; i++)
			{
				if (p[i] == CUtils.s_kOutput)
				{
					if (i + 1 < p.Length)
					{
						if (Path.GetExtension(p[i + 1]) == CProject.s_kObjFileExt)
						{
							return CUtils.combinePath(workingDir, p[i + 1]);
						}
					}
				}
			}
			return null;
		}

		//=======================================================================================================

		private static readonly char[] s_kSeparateChars = { ' ', '\t' };

		//=======================================================================================================

		private Process m_process;
		private string m_cmd;
		private string m_workingDir;
	}
}
