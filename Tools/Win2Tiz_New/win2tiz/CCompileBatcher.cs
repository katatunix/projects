using System;
using System.Collections.Generic;
using System.Threading;

using win2tiz.utils;

namespace win2tiz
{
	class CCompileBatcher : ICompileNotifier
	{
		public CCompileBatcher(List<CMongccClientSocket> servers, int neededServersNumber)
		{
			for (int i = 0; i < s_kMaxLocalThreads; i++)
			{
				m_localThreads[i] = null;
			}

			if (servers != null && servers.Count > 0)
			{
				m_remoteThreadCount = s_kMaxRemoteThreads < servers.Count ? s_kMaxRemoteThreads : servers.Count;
			}
			else
			{
				m_remoteThreadCount = 0;
			}

			for (int i = 0; i < m_remoteThreadCount; i++)
			{
				m_remoteThreads[i] = new CRemoteThread(servers[i]);
			}

			m_serverOrders =  m_remoteThreadCount > 0 ? new int[m_remoteThreadCount] : null;

			if (servers == null)
				m_neededServersNumber = 0;
			else
				m_neededServersNumber = neededServersNumber == 0 ? servers.Count : neededServersNumber;

			m_lastTick = 0;
		}

		public bool compile(List<TCommand> commands, int jobs, ICompileNotifier notifier)
		{
			if (isRunning()) return false;

			setRunning(true);
			setKillProcess(false);

			CUtils.shakeOrder(m_serverOrders);

			m_notifier = notifier;

			if (jobs < 1) jobs = 1;
			if (jobs > s_kMaxLocalThreads) jobs = s_kMaxLocalThreads;

			m_locals.Clear();

			m_lastTick = Environment.TickCount;

			foreach (TCommand cmd in commands)
			{
				if (cmd.type == ECommandType.eCompile)
				{
					compileSingle(cmd, jobs, cmd.useMongcc);
				}
				if (!isRunning()) break;
			}

			// Must wait here because we want a final value of m_locals
			waitingForFinishAll(jobs);

			// Process the failed list from Mongcc
			if (m_locals.Count > 0 && isRunning())
			{
				CConsole.writeMongcc("Local compile " + m_locals.Count + " file(s)\n");
				foreach (TCommand cmd in m_locals)
				{
					// Make sure cmd is a compile cmd
					compileSingle(cmd, jobs, false);
					if (!isRunning()) break;
				}
			}

			waitingForFinishAll(jobs);

			setRunning(false);

			return true;
		}

		public void forceStop(bool isKillProcess)
		{
			setKillProcess(isKillProcess);
			setRunning(false);
		}

		//=======================================================================================

		private bool isRunning()
		{
			lock (m_lockRunning)
			{
				return m_running;
			}
		}

		private void setRunning(bool r)
		{
			lock (m_lockRunning)
			{
				m_running = r;
			}
		}

		private bool isKillProcess()
		{
			lock (m_lockKillProc)
			{
				return m_isKillProcess;
			}
		}

		private void setKillProcess(bool b)
		{
			lock (m_lockKillProc)
			{
				m_isKillProcess = b;
			}
		}

		/// <summary>
		/// 
		/// </summary>
		/// <param name="cmd"></param>
		/// <param name="jobs"></param>
		/// <param name="useMongcc">use this, ignore cmd.useMongcc</param>
		private void compileSingle(TCommand cmd, int jobs, bool useMongcc)
		{
			int freeSlotLocal = -1;
			int freeSlotRemote = -1;
			while (true)
			{
				int countRunningLocalJobs = 0;
				// Is there any running UB job?
				bool hasUB = false;
				for (int j = 0; j < jobs; j++)
				{
					if ( m_localThreads[j] != null && m_localThreads[j].isRunning() )
					{
						countRunningLocalJobs++;
						if ( m_localThreads[j].isUnityBuild() )
						{
							hasUB = true;
						}
					}
				}

				// Don't run another local job if there's running UB job
				if (!hasUB)
				{
					for (int j = 0; j < jobs; j++)
					{
						if (m_localThreads[j] == null)
						{
							m_localThreads[j] = new CLocalThread();
							freeSlotLocal = j;
							break;
						}
						if (!m_localThreads[j].isRunning())
						{
							freeSlotLocal = j;
							break;
						}
					}
				}
				if (freeSlotLocal > -1) break;

				if (useMongcc && m_remoteThreadCount > 0)
				{
					for (int j = 0; j < m_remoteThreadCount; j++)
					{
						if (m_remoteThreads[m_serverOrders[j]].isReady())
						{
							freeSlotRemote = m_serverOrders[j];
							break;
						}
					}

					if (freeSlotRemote > -1) break;
				}

				if (!isRunning()) return;

				// No free slot, try to connect other mongcc servers
				if (useMongcc && m_remoteThreadCount > 0)
				{
					int liveServers = 0;
					for (int j = 0; j < m_remoteThreadCount; j++)
					{
						if (m_remoteThreads[j].isConnected())
						{
							liveServers++;
						}
					}
					if (liveServers < m_neededServersNumber)
					{
						for (int j = 0; j < m_remoteThreadCount; j++)
						{
							CRemoteThread remote = m_remoteThreads[j];
							if (!remote.isConnected() && remote.canConnect())
							{
								if (remote.connect())
								{
									CConsole.writeMongcc("Successful connected to " + remote.getHostName() + "\n");
									liveServers++;
									if (liveServers == m_neededServersNumber) break;
								}
								else
								{
									CConsole.writeWarning("Could not connected to " + remote.getHostName() + "\n");
								}
							}
						}
					}
				}

				checkPatient(countRunningLocalJobs);

				Thread.Sleep(100);
			}

			if (freeSlotLocal > -1)
			{
				m_localThreads[freeSlotLocal].start(cmd, this); // start thread
			}
			else if (useMongcc && freeSlotRemote > -1)
			{
				m_remoteThreads[freeSlotRemote].start(cmd, this); // start thread
			}
		}

		private void waitingForFinishAll(int jobs)
		{
			while (true)
			{
				bool finish = true;
				int liveLocal = 0;
				for (int i = 0; i < jobs; i++)
				{
					if (m_localThreads[i] != null && m_localThreads[i].isRunning())
					{
						finish = false;
						liveLocal++;
					}
				}
				if (finish)
				{
					for (int i = 0; i < m_remoteThreadCount; i++)
					{
						if (m_remoteThreads[i].isRunning())
						{
							finish = false;
							break;
						}
					}
				}

				if (finish) break;

				if (!isRunning() && isKillProcess()) forceStopAllThreads(jobs);

				checkPatient(liveLocal);

				Thread.Sleep(100);
			}
		}

		private void forceStopAllThreads(int jobs)
		{
			for (int i = 0; i < jobs; i++)
			{
				if (m_localThreads[i] != null && m_localThreads[i].isRunning())
				{
					m_localThreads[i].forceStop();
				}
			}
			for (int i = 0; i < m_remoteThreadCount; i++)
			{
				if (m_remoteThreads[i].isRunning())
				{
					m_remoteThreads[i].forceStop();
				}
			}
		}

		private void checkPatient(int jobsLocal)
		{
			int runningServers = 0;
			for (int j = 0; j < m_remoteThreadCount; j++)
			{
				if (m_remoteThreads[j].isRunning())
				{
					runningServers++;
				}
			}

			int totalJobs = jobsLocal + runningServers;

			if (Environment.TickCount - m_lastTick > s_kPatientTimeout)
			{
				CConsole.writeTime("Still compiling at least " + totalJobs + " file(s), please be patient\n");
				m_lastTick = Environment.TickCount;
			}
		}
		
		//=======================================================================================

		public void onFinishCompile(TCommand cmd, TProcessResult pr)
		{
			m_lastTick = Environment.TickCount;

			if (pr.usedMongcc && !pr.wasExec)
			{
				m_locals.Add(cmd);
			}
			if (m_notifier != null)
			{
				m_notifier.onFinishCompile(cmd, pr);
			}
		}

		//=======================================================================================

		private static readonly int s_kMaxLocalThreads = 8;
		private static readonly int s_kMaxRemoteThreads = 16;
		private static readonly int s_kPatientTimeout = 60000;

		private CLocalThread[] m_localThreads = new CLocalThread[s_kMaxLocalThreads];
		private CRemoteThread[] m_remoteThreads = new CRemoteThread[s_kMaxRemoteThreads];

		private int m_remoteThreadCount = 0;

		private Object m_lockRunning = new Object();
		private Object m_lockKillProc = new Object();

		private bool m_running = false;
		private bool m_isKillProcess = false;

		private ICompileNotifier m_notifier = null;

		private List<TCommand> m_locals = new List<TCommand>();

		private int[] m_serverOrders;

		private int m_neededServersNumber;

		private int m_lastTick;
	}
}
