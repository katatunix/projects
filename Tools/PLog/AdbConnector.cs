using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;
using System.Diagnostics;
using System.IO;

namespace PLog
{
	class MyAsyncInfo
	{
		public byte[] m_buffer;
		public Stream m_stream;
		public int m_sessionId;

		public MyAsyncInfo(byte[] buffer, Stream stream, int sessionId)
		{
			m_buffer = buffer;
			m_stream = stream;
			m_sessionId = sessionId;
		}
	}

	class AdbConnector
	{
		private static AdbConnector m_sInstance = null;

		private const String ADB = "adb";
		private const int MAX_BUFFER_LEN = 2 * 1024 * 1024; // 2 MB

		private static int m_sSessionCounter = 0;

		private IAdbObserver m_observer;
		private Process m_process;

		private int m_sessionId;

		public static AdbConnector getInstance()
		{
			if (m_sInstance == null)
			{
				m_sInstance = new AdbConnector();
			}
			return m_sInstance;
		}

		private AdbConnector()
		{
			m_observer = null;
			m_process = null;
			m_sessionId = -1;
		}

		public int getSessionId()
		{
		    return m_sessionId;
		}

		public bool start(IAdbObserver observer, String dev)
		{
			if (m_sessionId > -1) return false;
			
			prepareProcess(dev);
			m_observer = observer;

			try
			{
				m_process.Start();
			}
			catch (Exception)
			{
				safeKillProcess();
				notifyComplete();
				m_observer = null;
				return false;
			}

			m_observer = observer;
			m_sessionId = m_sSessionCounter++;

			byte[] buffer = new byte[MAX_BUFFER_LEN];

			m_process.StandardOutput.BaseStream.BeginRead(
				buffer, 0, MAX_BUFFER_LEN, readCallback,
				new MyAsyncInfo(
					buffer,
					m_process.StandardOutput.BaseStream,
					m_sessionId
				)
			);

			return true;
		}

		public void readCallback(IAsyncResult asyncResult)
		{
			MyAsyncInfo info = (MyAsyncInfo)asyncResult.AsyncState;
			if (info.m_sessionId != m_sessionId) return;
			Stream stream = info.m_stream;

			int lenRead = 0;
			try
			{
				lenRead = stream.EndRead(asyncResult);
			}
			catch (Exception)
			{
				lenRead = 0;
			}

			if (info.m_sessionId != m_sessionId) return;

			if (lenRead > 0)
			{
				StringBuilder sb = new StringBuilder();
				for (int i = 0; i < lenRead; i++)
				{
					sb.Append((char)info.m_buffer[i]);
				}

				notifyReceive(sb.ToString(), m_sessionId);

				if (info.m_sessionId != m_sessionId) return;
				try
				{
					Thread.Sleep(50);
					stream.BeginRead(info.m_buffer, 0, MAX_BUFFER_LEN, readCallback, info);
					if (info.m_sessionId != m_sessionId) return;
				}
				catch (Exception)
				{
					lenRead = 0;
				}
			}

			if (info.m_sessionId != m_sessionId) return;
			
			if (lenRead == 0)
			{
				notifyComplete();
				
				safeKillProcess();

				m_observer = null;
				m_sessionId = -1;
			}
		}

		public void stop() // force stop, run on GUI thread
		{
			if (m_sessionId == -1) return;

			m_observer = null;

			safeKillProcess();

			m_sessionId = -1;
		}

		private void prepareProcess(String dev)
		{
			m_process = new Process();

			m_process.StartInfo.FileName = ADB;
			m_process.StartInfo.Arguments = "-s " + dev + " logcat";

			m_process.StartInfo.CreateNoWindow = true;
			m_process.StartInfo.UseShellExecute = false;
			m_process.StartInfo.RedirectStandardOutput = true;
		}

		private void safeKillProcess()
		{
			if (m_process != null)
			{
				try
				{
					m_process.StandardOutput.BaseStream.Close();
					m_process.Kill();
					m_process.Close();
					m_process.Dispose();
				}
				catch (Exception)
				{
				}
				m_process = null;
			}
		}

		private void notifyComplete()
		{
			if (m_observer != null)
			{
				m_observer.onComplete();
			}
		}

		private void notifyReceive(String log, int sessionId)
		{
			if (m_observer != null)
			{
				m_observer.onReceive(log, sessionId);
			}
		}

		// Utils
		public static void clearLogcat(String dev)
		{
			Process p = new Process();
			p.StartInfo.FileName = ADB;
			p.StartInfo.Arguments = "-s " + dev + " logcat -c";

			p.StartInfo.CreateNoWindow = true;
			p.StartInfo.UseShellExecute = false;
			p.StartInfo.RedirectStandardOutput = true;

			try
			{
				p.Start();
				p.WaitForExit(2000);
			}
			catch (Exception)
			{
			}

			try { p.Kill(); p.Close(); p.Dispose(); }
			catch (Exception) { }
		}

        public static bool startServer()
        {
            Process p = new Process();
            p.StartInfo.FileName = ADB;
            p.StartInfo.Arguments = "start-server";

            try
            {
                p.Start();
                p.WaitForExit();
            }
            catch (Exception)
            {
                return false;
            }

            try { p.Kill(); p.Close(); p.Dispose(); }
            catch (Exception) { }

            return true;
        }

		public static List<String> getDevicesList()
		{
            if (!startServer()) return null;

			Process p = new Process();
			p.StartInfo.FileName = ADB;
			p.StartInfo.Arguments = "devices";

			p.StartInfo.CreateNoWindow = true;
			p.StartInfo.UseShellExecute = false;
			p.StartInfo.RedirectStandardOutput = true;

			try
			{
				p.Start();
				const int MAX_BUF = 512;
				char[] buf = new char[MAX_BUF];
				String txt = "";
				while (!p.HasExited)
				{
					int len = p.StandardOutput.Read(buf, 0, MAX_BUF);
					if (len > 0)
					{
						txt += new String(buf, 0, len);
					}
				}
				try { p.Kill(); p.Close(); p.Dispose(); }
				catch (Exception) { }

				List<String> list = new List<String>();
				String[] keys = { "\r\n", "\r\r\n" };
				String[] lines = txt.Split(keys, StringSplitOptions.RemoveEmptyEntries);
				for (int i = 0; i < lines.Length; i++)
				{
					if (String.IsNullOrEmpty(lines[i])) continue;
					int index = lines[i].IndexOf("\t");
					if (index == -1) continue;
                    String dev = lines[i].Substring(0, index).Trim();
                    String model = getModel(dev);
					list.Add(model + " " + dev);
				}
				return list;
			}
			catch (Exception)
			{
				try { p.Kill(); p.Close(); p.Dispose(); }
				catch (Exception) { }
				return null;
			}
		}

        public static String getModel(String dev)
        {
            Process p = new Process();
            p.StartInfo.FileName = "adb";
            p.StartInfo.Arguments = "-s " + dev + " shell getprop";

            p.StartInfo.CreateNoWindow = true;
            p.StartInfo.UseShellExecute = false;
            p.StartInfo.RedirectStandardOutput = true;

            String model = "";
            try
            {
                p.Start();
                String s = p.StandardOutput.ReadToEnd();
                String[] keys = { "\r\r\n" };
                String[] lines = s.Split(keys, StringSplitOptions.RemoveEmptyEntries);
                for (int i = 0; i < lines.Length; i++)
                {
                    if (lines[i].StartsWith("[ro.product.model]"))
                    {
                        int index = lines[i].IndexOf(":");
                        model = lines[i].Substring(index + 1).Trim();
                        break;
                    }
                }
                p.WaitForExit();
            }
            catch (Exception)
            {
            }

            try { p.Kill(); p.Close(); }
            catch (Exception) { }

            return model;
        }

	}
}
