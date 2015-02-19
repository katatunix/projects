using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using win2tiz.utils;

namespace win2tiz
{
	class CBuilder : ICompileNotifier
	{
		public CBuilder()
		{
			m_lock = new Object();
			m_servers = null;
			m_compileBatcher = null;
			m_isVerbose = false;
			m_countCompile = 0;
			m_totalCompile = 0;
			m_commands = new List<TCommand>();
		}

		public void clearCommands()
		{
			m_commands.Clear();
			m_totalCompile = 0;
		}

		public void addCommand(TCommand cmd)
		{
			m_commands.Add(cmd);
			if (cmd.type == ECommandType.eCompile)
			{
				m_totalCompile++;
			}
		}

		public void addCommands(List<TCommand> list)
		{
			foreach (TCommand cmd in list)
			{
				addCommand(cmd);
			}
		}

		public bool build(bool isVerbose, int jobs,
			string[] hosts = null, int port = 0,
			int neededServersNumber = 0, int timeout = 0, int retryConnectTime = 0)
		{
			CConsole.writeInfo("Total: " + m_totalCompile + " files to be compiled\n\n");

			disconnectMongcc(); // this will set m_servers to null

			connectMongcc(hosts, port, neededServersNumber, timeout, retryConnectTime, jobs); // this will create m_servers

			m_compileBatcher = new CCompileBatcher(m_servers, neededServersNumber);

			m_isVerbose = isVerbose;

			// Compile

			lock (m_lock)
			{
				m_countCompile = 0;
			}

			if (m_totalCompile > 0)
			{
				m_compileBatcher.compile(m_commands, jobs, this);
				CConsole.writeLine();
			}
			
			disconnectMongcc();

			lock (m_lock)
			{
				if (m_countCompile == -1) return false; // stop build when compile error
			}

			// Link static
			if (!executeAllCommandsWithType(ECommandType.eLinkStatic)) return false;

			// Link dynamic
			if (!executeAllCommandsWithType(ECommandType.eLinkDynamic)) return false;

			// Generate DSYM
			if (!executeAllCommandsWithType(ECommandType.eGenerateDsym)) return false;

			// Copy
			if (!executeAllCommandsWithType(ECommandType.eCopy)) return false;

			// Strip
			if (!executeAllCommandsWithType(ECommandType.eStrip)) return false;

			return true;
		}

		public void forceStop()
		{
			lock (m_lock)
			{
				m_countCompile = -1;
				if (m_compileBatcher != null)
				{
					m_compileBatcher.forceStop(true);
				}
			}
		}

		public void onFinishCompile(TCommand cmd, TProcessResult pr)
		{
			lock (m_lock)
			{
				if (pr.usedMongcc && !pr.wasExec)
				{
					CConsole.writeWarning(
						string.Format("Warning: {0} could not compile {1} for project {2}, don't worry, it will be local compiled later\n",
						pr.mongccServerName, cmd.alias, cmd.prjName)
					);
				}
				else
				{
					if (m_countCompile == -1) return;

					m_countCompile++;
					CConsole.writeInfo(m_countCompile + "/" + m_totalCompile +
						". " + cmd.prjName + ". Compile: " + cmd.alias);
					if (m_isVerbose && !string.IsNullOrEmpty(cmd.verboseString))
					{
						CConsole.writeVerbose("\n" + cmd.verboseString);
					}
					if (!checkProcessResult(pr))
					{
						m_countCompile = -1;
						if (m_compileBatcher != null)
						{
							m_compileBatcher.forceStop(false);
						}
					}
				}
			}
		}

		//=============================================================================

		private void connectMongcc(string[] hosts, int port,
			int neededServersNumber, int timeout, int retryConnectTime, int localJobs)
		{
			m_servers = null;

			if (hosts != null) // mongcc is enabled
			{
				if (m_totalCompile <= localJobs)
				{
					if (m_totalCompile > 0)
						CConsole.writeMongcc("mongcc is disabled due to small number of recompiled files\n\n");
				}
				else
				{
					CConsole.writeInfoLine("Needed mongcc servers number: " + (neededServersNumber == 0 ? "full" : neededServersNumber.ToString()));

					if (hosts.Length > 0)
					{
						CConsole.writeInfo(string.Format("Try connecting to {0} mongcc server(s) at port {1}:",
							hosts.Length,
							port
						));
						foreach (string host in hosts)
						{
							CConsole.writeInfo(" " + host);
						}
						CConsole.writeLine();
						m_servers = CMongccClientSocket.createList(hosts, port,
							timeout,
							neededServersNumber,
							retryConnectTime
						);

						int successNumber = 0;
						foreach (CMongccClientSocket server in m_servers)
						{
							if (server.isConnected())
							{
								successNumber++;
							}
						}
						if (successNumber > 0)
						{
							CConsole.writeInfo(string.Format("Successful connected to {0} mongcc server(s):", successNumber));
							foreach (CMongccClientSocket server in m_servers)
							{
								if (server.isConnected())
								{
									CConsole.writeInfo(" " + server.getHostName());
								}
							}
							CConsole.write("\n\n");
						}
						else
						{
							CConsole.writeWarning("Warning: Could not connect to any mongcc server, fail to use mongcc!\n\n");
						}
					}
					else
					{
						CConsole.writeWarning("Warning: Could not found any mongcc server, fail to use mongcc!\n\n");
					}
				}
			}
		}

		private void disconnectMongcc()
		{
			if (m_servers != null)
			{
				foreach (CMongccClientSocket server in m_servers)
				{
					server.disconnect();
				}
				m_servers = null;
			}
		}

		private bool executeAllCommandsWithType(ECommandType type)
		{
			
			foreach (TCommand cmd in m_commands)
			{
				if (cmd.type != type || cmd.type == ECommandType.eCompile) continue;

				switch (type)
				{
					case ECommandType.eLinkStatic:
						CConsole.writeInfo("Link static: " + cmd.alias);
						break;
					case ECommandType.eLinkDynamic:
						CConsole.writeInfo("Link dynamic: " + cmd.alias);
						break;
					case ECommandType.eGenerateDsym:
						CConsole.writeInfo("Generate DSYM: " + cmd.alias);
						break;
					case ECommandType.eCopy:
						CConsole.writeInfo("Copy: " + cmd.alias);
						break;
					case ECommandType.eStrip:
						CConsole.writeInfo("Strip: " + cmd.alias);
						break;
				}
				
				if (m_isVerbose)
				{
					if (type == ECommandType.eCopy)
					{
						CConsole.writeVerbose("\n" + cmd.command + " -> " + cmd.verboseString);
					}
					else if (!string.IsNullOrEmpty(cmd.verboseString))
					{
						CConsole.writeVerbose("\n" + cmd.verboseString);
					}
				}

				bool ok = true;

				if (type == ECommandType.eCopy)
				{
					string error = null;
					try
					{
						File.Copy(cmd.command, cmd.verboseString, true);
					}
					catch (Exception ex)
					{
						error = ex.Message;
					}
					if (error == null)
					{
						CConsole.writeSuccess(" (success)\n");
					}
					else
					{
						CConsole.writeError(" (error)\n" + error + "\n");
						ok = false;
					}
				}
				else
				{
					TProcessResult pr = CProcessHelper.executeStatic(cmd.command, cmd.workingDir);
					if (!checkProcessResult(pr)) ok = false;
				}

				CConsole.writeLine();

				if (!ok) return false;
			}

			return true;
		}

		private bool checkProcessResult(TProcessResult pr)
		{
			int time = pr.timestamp;
			if (time > 0)
			{
				CConsole.writeTimeStamp(" (" + (float)time / 1000.0f + "s)");
			}
			bool error = !pr.wasExec || pr.exitCode != 0;
			if (error)
			{
				CConsole.writeError(" (error)");
			}
			else
			{
				CConsole.writeSuccess(" (success)");
			}
			if (pr.usedMongcc)
			{
				CConsole.writeMongcc(string.Format(" (used mongcc: {0})", pr.mongccServerName));
			}
			CConsole.writeLine();

			// Only print output text when error?
			if (!string.IsNullOrEmpty(pr.outputText) && (m_isVerbose || error))
			{
				CConsole.writeOutputText(pr.outputText + "\n");
			}
			return !error;
		}

		//=============================================================================

		private List<CMongccClientSocket> m_servers;
		private CCompileBatcher m_compileBatcher;

		private bool m_isVerbose;
		private int m_countCompile;
		private int m_totalCompile;

		private List<TCommand> m_commands;

		private Object m_lock;
	}
}
