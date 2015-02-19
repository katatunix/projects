using System;
using System.Threading;

namespace win2tiz
{
	class CLocalThread
	{
		public CLocalThread()
		{
			m_threadStart = new ThreadStart(callback);
			m_process = new CProcessHelper();
			m_notifier = null;
		}

		/// <summary>
		/// Make sure isRunning() == false
		/// </summary>
		public void start(TCommand cmd, ICompileNotifier notifier)
		{
			setRunning(true);

			m_cmd = cmd;
			m_notifier = notifier;

			new Thread(m_threadStart).Start();
		}

		public bool isRunning()
		{
			lock (m_lockRunning)
			{
				return m_running;
			}
		}

		public void forceStop()
		{
			m_process.forceStop();
		}

		public bool isUnityBuild()
		{
			return m_cmd.alias.StartsWith("UB_") || m_cmd.alias.StartsWith("CU_");
		}

		public string getSourceFileName()
		{
			return m_cmd.alias;
		}

		//========================================================================================

		private void setRunning(bool r)
		{
			lock (m_lockRunning)
			{
				m_running = r;
			}
		}

		private void callback()
		{
			int oldTick = System.Environment.TickCount;

			TProcessResult pr = m_process.execute(m_cmd.command, m_cmd.workingDir);

			pr.timestamp = System.Environment.TickCount - oldTick;

			if (m_notifier != null)
			{
				m_notifier.onFinishCompile(m_cmd, pr);
			}

			setRunning(false);
		}

		//=====================================================================================

		private ThreadStart m_threadStart;

		private CProcessHelper m_process;

		private Object m_lockRunning = new Object();
		private bool m_running = false;

		private TCommand m_cmd;
		private ICompileNotifier m_notifier;		
	}
}
