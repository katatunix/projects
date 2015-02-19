using System;
using System.IO;
using System.Net.Sockets;
using System.Threading;
using System.Collections.Generic;

using win2tiz.message;
using win2tiz.utils;

namespace win2tiz
{
	class CMongccServerSocket : AMongccSocket
	{
		public CMongccServerSocket(IMongccServer server) : base()
		{
			m_threadStart = new ThreadStart(callback);
			m_state = EState.eFree;
			m_server = server;
			m_lockState = new Object();

			m_processCompile = new CProcessHelper();
		}

		public bool start(Socket socket, string tempDir)
		{
			if (!isFree()) return false;
			
			setState(EState.eWaiting);

			m_listRoot.Clear();
			m_debugPrefixMap = "";
			
			m_socket = socket;
			m_socket.ReceiveTimeout = s_kTimeout;

			m_tempDir = tempDir;

			new Thread(m_threadStart).Start();

			return true;
		}

		public void forceStop()
		{
			m_processCompile.forceStop();

			shutdown();
		}

		public bool isFree()
		{
			return getState() == EState.eFree;
		}

		public bool isCompiling()
		{
			return getState() == EState.eCompiling;
		}

		//===========================================================================================
		//===========================================================================================

		private void callback()
		{
			string clientId = m_socket.RemoteEndPoint.ToString() + "_" + getNewSessionId();

			string sessionFolderPath = CUtils.combinePath(
				Environment.CurrentDirectory,
				m_tempDir + "\\" + makeValidPath(clientId)
			) + "\\";

			try
			{
				if (Directory.Exists(sessionFolderPath))
				{
					Directory.Delete(sessionFolderPath, true);
				}
				Directory.CreateDirectory(sessionFolderPath);
			}
			catch (Exception)
			{
			}

			while (true)
			{
				CConsole.writeInfoLine(string.Format("{0} Waiting for a message...", cur(clientId)));

				CMessage msg = readMessage();
				if (msg == null) // disconnect
				{
					break;
				}

				switch (msg.getTypeEnum())
				{
					case EMessageType.eFreeNumRequest:
					{
						handleMessageFreeNum(clientId);
						break;
					}

					case EMessageType.eFile:
					{
						handleMessageFile(clientId, sessionFolderPath, msg);
						break;
					}

					case EMessageType.eCompileRequest:
					{
						setState(EState.eCompiling);
						handleMessageCompile(clientId, sessionFolderPath, msg);
						setState(EState.eWaiting);
						break;
					}
				}
			}

			// Finish the session
			if (m_socket != null)
			{
				shutdown();
				m_socket.Close();
				m_socket = null;
			}
			
			try
			{
				if (Directory.Exists(sessionFolderPath))
				{
					Directory.Delete(sessionFolderPath, true);
				}
			}
			catch (Exception)
			{
				CConsole.writeInfoLine(string.Format("{0} Warning: could not delete the temp dir {1}",
					cur(clientId), sessionFolderPath));
			}

			CConsole.writeInfoLine(string.Format("{0} Disconnected!", cur(clientId)));

			// Now we are free
			setState(EState.eFree);
		}

		private void handleMessageFreeNum(string clientId)
		{
			int num = m_server.getFreeNum();
			CConsole.writeInfoLine(string.Format("{0} Recv free num request, send response = {1}",
					cur(clientId), num));
			CMessage msg = CMessageFreeNumResponse.createMessage(num);
			writeMessage(msg);
		}

		private void handleMessageCompile(string clientId, string sessionFolderPath, CMessage msg)
		{
			CMessageCompileRequest msgCompile = new CMessageCompileRequest(msg);

			string cmd = msgCompile.getCmd();
			string[] p = cmd.Split(s_kSeparateChars, StringSplitOptions.RemoveEmptyEntries);

			cmd = "";
			string sourceFileName = null;
			for (int i = 0; i < p.Length; i++)
			{
				if (p[i].StartsWith(CUtils.s_kIncludePath))
				{
					cmd += CUtils.s_kIncludePath + sessionFolderPath + makeValidPath(p[i].Substring(2)) + " ";
				}
				else if (p[i] == CUtils.s_kCompile)
				{
					if (i + 1 < p.Length)
					{
						string filePathInThisMachine = sessionFolderPath + makeValidPath(p[i + 1]);

						// -fdebug-prefix-map=e:\x\10.218.9.115_53590_1\E_\=E:\
						if (m_debugPrefixMap.Length > 0)
						{
							cmd += m_debugPrefixMap + " ";
						}

						cmd += CUtils.s_kCompile + " " + filePathInThisMachine + " ";
						sourceFileName = Path.GetFileName(p[i + 1]);

						i++;
					}
				}
				else
				{
					cmd += p[i] + " ";
				}
			}
			cmd = cmd.Trim();

			CMessage respondMessage = null;
			bool ok = false;

			if (sourceFileName != null)
			{
				// Compile
				CConsole.writeInfoLine(string.Format("{0} Recv a compile request: {1}",
					cur(clientId), sourceFileName));

				TProcessResult pr = m_processCompile.execute(cmd, sessionFolderPath);

				if (!pr.wasExec || pr.exitCode != 0)
				{
					CConsole.writeInfoLine(
						string.Format("{0} Compile error: wasExec=[{1}], exitCode=[{2}], cmd=[{3}], outputText=[{4}]",
							cur(clientId), pr.wasExec, pr.exitCode, cmd, pr.outputText)
					);
					ok = false;
					respondMessage = CMessageCompileResponse.createMessage(
						pr.wasExec, pr.exitCode, pr.outputText, null, 0);
				}
				else
				{
					ok = true;
					string oFileName = Path.ChangeExtension(sourceFileName, "o");
					// Read the .o file from disk
					using (BinaryReader reader = new BinaryReader(File.OpenRead(sessionFolderPath + oFileName)))
					{
						int fileSize = (int)reader.BaseStream.Length;
						byte[] buffer = fileSize > 0 ? new byte[fileSize] : null;
						if (fileSize > 0)
						{
							reader.Read(buffer, 0, fileSize);
						}
						respondMessage = CMessageCompileResponse.createMessage(
							pr.wasExec, pr.exitCode, pr.outputText, buffer, fileSize);
					}
				}
			}
			else
			{
				ok = false;
				CConsole.writeInfoLine(string.Format("{0} Receive a compile request but it is a invalid command",
					cur(clientId)));
				respondMessage = CMessageCompileResponse.createMessage(false, 0,
					"error: Invalid compile command", null, 0);
			}

			CConsole.writeInfoLine(string.Format("{0} Send compile result: {1}, success = {2}",
				cur(clientId), sourceFileName != null ? sourceFileName : "no source file", ok));

			writeMessage(respondMessage);
		}

		private void handleMessageFile(string clientId, string sessionFolderPath, CMessage msg)
		{
			CMessageFile msgFile = new CMessageFile(msg);
			string filePath = msgFile.getFilePath();
			int fileSize = msgFile.getFileSize();
			CConsole.writeInfoLine(string.Format("{0} Recv file {1}|{2}", cur(clientId), filePath, fileSize));

			string saveFilePath = sessionFolderPath + makeValidPath(filePath);
			string saveFolderPath = Path.GetDirectoryName(saveFilePath);
			if (!Directory.Exists(saveFolderPath))
			{
				Directory.CreateDirectory(saveFolderPath);
			}

			using (BinaryWriter writer = new BinaryWriter(File.Open(saveFilePath, FileMode.Create)))
			{
				if (fileSize > 0)
				{
					writer.Write(msgFile.getData(), msgFile.getOffset(), fileSize);
				}
			}

			string clientRoot = CUtils.getPathRoot(filePath);
			if (!m_listRoot.Contains(clientRoot))
			{
				m_listRoot.Add(clientRoot);

				string serverRoot = sessionFolderPath + makeValidPath(clientRoot);
				if (m_debugPrefixMap.Length > 0)
				{
					m_debugPrefixMap += " ";
				}
				// -fdebug-prefix-map=e:\x\10.218.9.115_53590_1\E_\=E:\
				m_debugPrefixMap += CUtils.s_kDebugPrefixMap + serverRoot + "=" + clientRoot;
			}
		}

		private void setState(EState state)
		{
			lock (m_lockState)
			{
				m_state = state;
			}
		}

		private EState getState()
		{
			lock (m_lockState)
			{
				return m_state;
			}
		}

		//===========================================================================================
		//===========================================================================================

		private static string cur(string clientId)
		{
			return string.Format("[{0} {1}]", clientId, DateTime.Now);
		}
		
		private static int getNewSessionId()
		{
			lock (s_lockSession)
			{
				return ++s_sessionId;
			}
		}

		private static string makeValidPath(string path)
		{
			return path.Replace(':', '_');
		}

		//===========================================================================================
		//===========================================================================================

		private static readonly char[] s_kSeparateChars = { ' ', '\t' };

		private static readonly int s_kTimeout = 3600000;

		private static int s_sessionId = 0;
		private static Object s_lockSession = new Object();

		//===========================================================================================
		//===========================================================================================

		private enum EState
		{
			eFree = 0,
			eWaiting,
			eCompiling
		};

		private ThreadStart m_threadStart;
		private EState m_state;
		private string m_tempDir;
		private IMongccServer m_server;

		private Object m_lockState;

		private CProcessHelper m_processCompile;

		private List<string> m_listRoot = new List<string>();
		private string m_debugPrefixMap;
	}
}
