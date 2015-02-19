using System;
using System.Net.Sockets;
using System.Threading;

using win2tiz.message;
using win2tiz.utils;

namespace win2tiz
{
	/// <summary>
	/// Depend on:
	/// CMessage
	/// CUtils
	/// </summary>
	abstract class AMongccSocket
	{
		public AMongccSocket()
		{
			m_socket = null;
			m_buffer = new byte[s_kBufferSize];
			m_offset = 0;
			m_len = 0;
		}

		public CMessage readMessage()
		{
			if (m_socket == null) return null;

			CMessage msg = new CMessage();

			while (true)
			{
				while (m_len > 0)
				{
					int k = msg.consume(m_buffer, m_offset, m_offset + m_len - 1);
					CUtils.assert(k > 0);
					m_offset += k;
					m_len -= k;

					if (msg.isFull()) return msg;
				}

				// Now m_len == 0
				m_offset = 0;
				
				while (true)
				{
					bool retry = false;
					try
					{
						m_len = m_socket.Receive(m_buffer, 0, m_buffer.Length, SocketFlags.None);
					}
					catch (SocketException ex)
					{
						if (ex.SocketErrorCode == SocketError.WouldBlock ||
							ex.SocketErrorCode == SocketError.IOPending ||
							ex.SocketErrorCode == SocketError.NoBufferSpaceAvailable)
						{
							// socket buffer is probably empty, wait and try again
							retry = true;
						}
						else
						{
							m_len = 0;
						}
					}
					catch (Exception)
					{
						m_len = 0;
					}

					if (retry)
					{
						Thread.Sleep(100);
						continue;
					}
					
					if (m_len == 0)
					{
						shutdown();
						m_socket.Close();
						m_socket = null;
						return null;
					}

					// m_len > 0, now consume it
					break;
				}
			}
		}

		public bool writeMessage(CMessage msg)
		{
			if (msg == null) return false;
			
			int len = msg.getLength();
			byte[] data = msg.getData();

			try
			{
				byte[] bytes = new byte[4];
				
				CUtils.int2bytes(msg.getType(), bytes);
				m_socket.Send(bytes, 0, 4, SocketFlags.None);

				CUtils.int2bytes(len, bytes);
				m_socket.Send(bytes, 0, 4, SocketFlags.None);

				if (len > 0)
				{
					CUtils.assert(data != null);
					m_socket.Send(data, 0, len, SocketFlags.None);
				}

				return true;
			}
			catch (Exception)
			{
				shutdown();
				m_socket.Close();
				m_socket = null;
				return false;
			}
		}

		public void shutdown()
		{
			if (m_socket != null)
			{
				try
				{
					m_socket.Shutdown(SocketShutdown.Both);
				}
				catch (Exception)
				{
				}
			}
		}

		//=====================================================================================================

		private static readonly int s_kBufferSize = 1024 * 1024 * 2; // 2 MB

		protected Socket m_socket;

		private byte[] m_buffer;
		private int m_offset;
		private int m_len;
	}
}
