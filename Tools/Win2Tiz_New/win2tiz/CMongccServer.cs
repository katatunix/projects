using System;
using System.Net;
using System.Net.Sockets;
using System.Threading;

using win2tiz.utils;

namespace win2tiz
{
	/// <summary>
	/// Depend on:
	/// CMongccServerSocket
	/// CConsole
	/// </summary>
	class CMongccServer : IMongccServer
	{
		public CMongccServer()
		{
			m_handers = null;
			m_running = false;
			m_listener = null;
		}

		public void start(int port, int backlog, string tempDir)
		{
			if (isRunning()) return;
			setRunning(true);

			if (backlog <= 0 || backlog > s_kMaxHandlers) return;

			m_handers = new CMongccServerSocket[backlog];
			for (int i = 0; i < m_handers.Length; i++)
			{
				m_handers[i] = new CMongccServerSocket(this);
			}

			IPAddress ipAd = IPAddress.Any;
			m_listener = new TcpListener(ipAd, port);
			m_listener.Start(backlog);

			CConsole.writeInfoLine("The server is running at port: " + port);
			CConsole.writeInfoLine("The local end point is: " + m_listener.LocalEndpoint);

			while (true)
			{
				CConsole.writeInfoLine("Waiting for a connection...");

				Socket socket = null;
				try
				{
					socket = m_listener.AcceptSocket();
				}
				catch (Exception)
				{
					break;
				}

				CConsole.writeInfoLine("Accept a connection from: " + socket.RemoteEndPoint);

				int slot = -1;
				for (int i = 0; i < m_handers.Length; i++)
				{
					if (m_handers[i].isFree())
					{
						slot = i;
						break;
					}
				}

				if (slot == -1)
				{
					CConsole.writeInfoLine("Full slot! Could not handle more connection!");
					byte[] tmp = new byte[1];
					tmp[0] = 0;
					socket.Send(tmp, 0, 1, SocketFlags.None);
					socket.Shutdown(SocketShutdown.Both);
					socket.Close();
				}
				else
				{
					byte[] tmp = new byte[1];
					tmp[0] = 1;
					socket.Send(tmp, 0, 1, SocketFlags.None);

					m_handers[slot].start(socket, tempDir);
				}
			}

			CConsole.writeWarning("Stop all handlers...\n");
			for (int i = 0; i < m_handers.Length; i++)
			{
				if (!m_handers[i].isFree())
				{
					m_handers[i].forceStop();
				}
			}

			CConsole.writeWarning("Waiting for all handlers finish...\n");
			bool finish = false;
			while (!finish)
			{
				Thread.Sleep(100);
				finish = true;
				for (int i = 0; i < m_handers.Length; i++)
				{
					if (!m_handers[i].isFree())
					{
						CConsole.writeWarning("Waiting for handler " + i + "...\n");
						finish = false;
						break;
					}
				}
			}

			CConsole.writeWarning("Server is stopped!\n");
		}

		public void forceStop()
		{
			if (m_listener != null)
			{
				CConsole.writeWarning("Stopping server can take long time, please be patient...\n");
				m_listener.Stop();
			}
		}

		public int getFreeNum()
		{
			if (m_handers == null || !isRunning()) return 0;
			int num = 0;
			for (int i = 0; i < m_handers.Length; i++)
			{
				if (!m_handers[i].isCompiling())
				{
					num++;
				}
			}
			return num;
		}

		//==========================================================================
		private bool isRunning()
		{
			lock (m_lock)
			{
				return m_running;
			}
		}

		private void setRunning(bool r)
		{
			lock (m_lock)
			{
				m_running = r;
			}
		}

		//==========================================================================
		private static readonly int s_kMaxHandlers = 8;

		//==========================================================================
		private CMongccServerSocket[] m_handers;
		private bool m_running;
		private Object m_lock = new Object();
		private TcpListener m_listener;
	}
}
