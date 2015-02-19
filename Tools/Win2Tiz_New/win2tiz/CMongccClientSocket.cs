using System;
using System.Collections.Generic;
using System.IO;
using System.Net.Sockets;

using win2tiz.message;
using win2tiz.utils;

namespace win2tiz
{
	/// <summary>
	/// Depend on:
	/// CMongccSocket
	/// CMessage
	/// CMessageFile
	/// CMessageCompileRequest
	/// CMessageFreeNumRequest
	/// </summary>
	class CMongccClientSocket : AMongccSocket
	{
		public static List<CMongccClientSocket> createList(string[] hosts, int port, int timeout,
			int neededNumber, int retryConnectTime)
		{
			List<CMongccClientSocket> list = new List<CMongccClientSocket>();
			
			int successCount = 0;

			if (hosts.Length > 0)
			{
				int[] orders = new int[hosts.Length];
				CUtils.shakeOrder(orders);
				for (int i = 0; i < orders.Length; i++)
				{
					string host = hosts[orders[i]];

					CMongccClientSocket client = new CMongccClientSocket(host, port, timeout, retryConnectTime);
					list.Add(client);
					
					if (neededNumber == 0 || successCount < neededNumber)
					{
						if (client.connect())
						{
							successCount++;
						}
					}
				}
			}
			return list;
		}

		//=============================================================================================

		private CMongccClientSocket(string host, int port, int timeout, int retryConnectTime) : base()
		{
			m_host = host;
			m_port = port;

			m_timeout = timeout;
			if (m_timeout <= 0)
			{
				m_timeout = s_kDefaultTimeout;
			}

			m_retryConnectTime = retryConnectTime;
			if (m_retryConnectTime < s_kRetryConnectTime)
			{
				m_retryConnectTime = s_kRetryConnectTime;
			}

			m_tcpClient = null;
			m_sentFiles = new List<string>();

			m_lastConnectTick = 0;
		}

		/// <summary>
		/// Make sure canConnect() == true
		/// </summary>
		/// <returns></returns>
		public bool connect()
		{
			disconnect();

			m_tcpClient = new TcpClient();
			try
			{
				m_tcpClient.Connect(m_host, m_port);
			}
			catch (Exception)
			{
				m_tcpClient = null;
				m_socket = null;
			}

			if (m_tcpClient != null)
			{
				m_socket = m_tcpClient.Client;
				m_socket.ReceiveTimeout = m_timeout;

				byte[] tmp = new byte[1];
				int pingLen = m_socket.Receive(tmp, 0, 1, SocketFlags.None);
				if (pingLen <= 0 || tmp[0] == 0)
				{
					disconnect();
				}
			}

			m_lastConnectTick = Environment.TickCount;

			return m_tcpClient != null;
		}

		public bool canConnect()
		{
			if (m_lastConnectTick == 0) return true;
			return Environment.TickCount - m_lastConnectTick >= m_retryConnectTime;
		}

		public void disconnect()
		{
			if (m_tcpClient != null)
			{
				if (m_socket != null)
				{
					shutdown();
					m_socket.Close();
				}
				
				m_tcpClient.Close();

				m_socket = null;
				m_tcpClient = null;
			}
			m_sentFiles.Clear();
		}

		public bool isConnected()
		{
			return m_tcpClient != null && m_socket != null;
		}

		/// <summary>
		/// 
		/// </summary>
		/// <returns>true if successful, otherwise false</returns>
		public bool sendFreeNumRequest()
		{
			if (!isConnected())
			{
				return false;
			}

			CMessage msg = CMessageFreeNumRequest.createMessage();
			return writeMessage(msg);
		}

		/// <summary>
		/// 
		/// </summary>
		/// <param name="path">Absolute path of the file to be sent</param>
		/// <returns>
		/// true		: the file was sent [ok]
		/// false		: could not send the file [error]
		/// </returns>
		public bool sendFile(string path)
		{
			if (!isConnected())
			{
				return false;
			}
			
			path = Path.GetFullPath(path);
			if (path[path.Length - 1] == '\\')
			{
				path = path.Substring(0, path.Length - 1);
			}

			string lowerPath = path.ToLower();

			if (m_sentFiles.Contains(lowerPath))
			{
				return true;
			}
			
			CMessage msg = CMessageFile.createMessage(path);
			bool ok = writeMessage(msg);
			if (ok)
			{
				m_sentFiles.Add(lowerPath);
			}
			
			return ok;
		}

		/// <summary>
		/// 
		/// </summary>
		/// <param name="compileCmd"></param>
		/// <returns>
		/// true		: the request was sent [ok]
		/// false		: could not send the request [error]
		/// </returns>
		public bool sendCompileRequest(string compileCmd)
		{
			if (!isConnected())
			{
				return false;
			}
			CMessage msg = CMessageCompileRequest.createMessage(compileCmd);
			return writeMessage(msg);
		}

		public string getHostName()
		{
			return m_host;
		}

		//================================================================================================

		private static readonly int s_kRetryConnectTime = 30000;
		private static readonly int s_kDefaultTimeout = 90000;

		private TcpClient m_tcpClient;

		private string m_host;
		private int m_port;
		private int m_timeout;
		private int m_retryConnectTime;

		private List<string> m_sentFiles;

		private int m_lastConnectTick;
	}
}
