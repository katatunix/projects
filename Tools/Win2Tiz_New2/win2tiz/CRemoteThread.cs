using System;
using System.IO;
using System.Threading;

using win2tiz.message;
using win2tiz.utils;

namespace win2tiz
{
	class CRemoteThread
	{
		public CRemoteThread(CMongccClientSocket socket)
		{
			m_socket = socket;
			m_threadStart = new ThreadStart(callback);
			m_processHelper = new CProcessHelper();
			m_notifier = null;
		}

		/// <summary>
		/// Make sure isRunning() == false
		/// </summary>
		public void start(TCommand cmd, ICompileNotifier notifier)
		{
			if (isRunning() || !isConnected()) return;
			setRunning(true);

			m_cmd = cmd;
			m_notifier = notifier;

			new Thread(m_threadStart).Start();
		}

		public void forceStop()
		{
			m_processHelper.forceStop();
			m_socket.shutdown();
		}

		public bool isReady()
		{
			return !isRunning() && m_socket.isConnected();
		}

		public bool isConnected()
		{
			return m_socket.isConnected();
		}

		public bool canConnect()
		{
			return m_socket.canConnect();
		}

		public bool connect()
		{
			return m_socket.connect();
		}

		public string getHostName()
		{
			return m_socket.getHostName();
		}

		/// <summary>
		/// Make sure isReadyToSend() is true, we don't check again here for better performance
		/// </summary>
		/// <returns></returns>
		public int requestFreeNum()
		{
			if (!m_socket.sendFreeNumRequest()) return 0;

			CMessage msg = m_socket.readMessage(); // block for waiting message
			if (msg == null) return 0;

			CMessageFreeNumResponse freeNumMsg = new CMessageFreeNumResponse(msg);
			return freeNumMsg.getNum();
		}

		public bool isRunning()
		{
			lock (m_lockRunning)
			{
				return m_running;
			}
		}

		//==============================================================================================
		//==============================================================================================

		private void callback()
		{
			int oldTick = System.Environment.TickCount;
			TProcessResult pr;

			// Remove the -o option as we don't need to compile
			string cmdToSend = "";
			string outFilePath = null;
			string[] p = m_cmd.command.Split(s_kSeparateChars, StringSplitOptions.RemoveEmptyEntries);
			for (int i = 0; i < p.Length; i++)
			{
				if (p[i] == CUtils.s_kOutput)
				{
					if (i + 1 < p.Length)
					{
						outFilePath = p[i + 1];
					}
					i += 1;
				}
				else
				{
					cmdToSend += p[i] + " ";
				}
			}

			string hostName = m_socket.getHostName();

			if (outFilePath == null)
			{
				pr = new TProcessResult(false, true, hostName, 0,
					"error: [mongcc] [" + hostName +
					"] Could not get the output file (" + CUtils.s_kOutput + ") in the command: " + m_cmd);
				goto my_end;
			}
			cmdToSend = cmdToSend.Trim();

			// Get dependency files, include the source file (.cpp)

			string depFilePath = Path.ChangeExtension(outFilePath, "d");
			string depFilePathFull = CUtils.combinePath(m_cmd.workingDir, depFilePath);

			// Generate .d file
			string prepCmd = cmdToSend.Replace(
				" " + CUtils.s_kMakeDependencies,
				" " + CUtils.s_kMakeDependenciesOnly + " " + depFilePath
			);

			// Remove -g if any, just to make sure
			if (prepCmd.EndsWith(" " + CUtils.s_kGenDsym))
			{
				prepCmd = prepCmd.Substring(0, prepCmd.Length - CUtils.s_kGenDsym.Length - 1);
			}
			else
			{
				prepCmd = prepCmd.Replace(" " + CUtils.s_kGenDsym + " ", " ");
			}
			
			//-------------------------------------------------------------------------------------
			pr = m_processHelper.execute(prepCmd, m_cmd.workingDir);
			//-------------------------------------------------------------------------------------

			if (!pr.wasExec || pr.exitCode != 0)
			{
				goto my_end;
			}

			// note: should not compile .s file, but still add code here
			if (!File.Exists(depFilePathFull))
			{
				pr.wasExec = false;
				goto my_end;
			}

			// Read the .d file
			using (StreamReader streamReader = new StreamReader(depFilePathFull))
			{
				string line;
				while ((line = streamReader.ReadLine()) != null)
				{
					if (string.IsNullOrEmpty(line)) continue;

					string[] files = line.Split(s_kSeparateChars, StringSplitOptions.RemoveEmptyEntries);
					foreach (string dFilePath in files)
					{
						if (dFilePath[dFilePath.Length - 1] == ':') continue;
						if (dFilePath == "\\") continue;
						// Send the file
						if (!m_socket.sendFile(dFilePath))
						{
							pr = new TProcessResult(false, true,
								hostName, 0, "error: [mongcc] [" + hostName +
								"] Fail to send file to save: " + dFilePath);
							goto my_end;
						}
					}
				}
			}
			
			// Send compile request
			cmdToSend = cmdToSend.Replace(" " + CUtils.s_kMakeDependencies, "");
			if (!m_socket.sendCompileRequest(cmdToSend))
			{
				pr = new TProcessResult(false, true, hostName, 0,
					"error: [mongcc] [" + hostName + "] Fail to send compile request: " + m_cmd);
				goto my_end;
			}

			// Receive the compile result
			CMessage msg = m_socket.readMessage();
			if (msg == null)
			{
				pr = new TProcessResult(false, true, hostName, 0,
					"error: [mongcc] [" + hostName + "] Fail to receive the compile response: " + m_cmd);
				goto my_end;
			}

			// Save to disk
			CMessageCompileResponse msgCompileRes = new CMessageCompileResponse(msg);
			if (!msgCompileRes.getWasExec() || msgCompileRes.getExitCode() != 0)
			{
				pr = new TProcessResult(msgCompileRes.getWasExec(), true, hostName, msgCompileRes.getExitCode(),
					"error: [mongcc] " + msgCompileRes.getOutputText());
				goto my_end;
			}
			
			string outFilePathFull = CUtils.combinePath(m_cmd.workingDir, outFilePath);

			try
			{
				using (BinaryWriter writer = new BinaryWriter(File.Open(outFilePathFull, FileMode.Create)))
				{
					byte[] oFileData = msgCompileRes.getOFileData();
					int oFileSize = msgCompileRes.getOFileSize();
					int oFileOffset = msgCompileRes.getOFileOffset();
					if (oFileData != null && oFileSize > 0)
					{
						writer.Write(oFileData, oFileOffset, oFileSize);
					}
				}
				pr = new TProcessResult(true, true, hostName, msgCompileRes.getExitCode(),
					msgCompileRes.getOutputText());
			}
			catch (Exception ex)
			{
				pr = new TProcessResult(false, true, hostName, 0,
					"error: [mongcc] [" + hostName +
					"] Could not save the output file to disk: " + outFilePathFull + "[" + ex.Message + "]");
				goto my_end;
			}

			// Okay
			my_end:
			pr.timestamp = System.Environment.TickCount - oldTick;
			
			if (m_notifier != null)
			{
				m_notifier.onFinishCompile(m_cmd, pr);
			}

			setRunning(false);
		}

		private void setRunning(bool r)
		{
			lock (m_lockRunning)
			{
				m_running = r;
			}
		}

		//==============================================================================================
		//==============================================================================================

		private ThreadStart m_threadStart;

		private CProcessHelper m_processHelper;

		private bool m_running = false;
		private Object m_lockRunning = new Object();

		private TCommand m_cmd;
		private ICompileNotifier m_notifier;

		private CMongccClientSocket m_socket;
		
		private static readonly string[] s_kNewLineStrings = { "\r\n" };
		private static readonly char[] s_kSeparateChars = { ' ', '\t' };
	}
}
