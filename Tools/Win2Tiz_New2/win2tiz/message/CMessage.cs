﻿using System;

using win2tiz.utils;

namespace win2tiz.message
{
	/// <summary>
	/// Depend on:
	/// EMessageType
	/// CUtils
	/// </summary>
	class CMessage
	{
		public CMessage()
		{
			reset();
		}

		public CMessage(int type, int length, byte[] data)
		{
			m_type		= type;
			m_length	= length;
			m_data		= data;
			m_offset	= 8 + m_length;
		}

		public void reset()
		{
			m_offset		= 0;
			m_type			= 0;
			m_length		= 0;
			m_data			= null;
		}

		public void copyFrom(CMessage msg)
		{
			reset();

			m_offset = msg.m_offset;
			m_type = msg.m_type;
			m_length = msg.m_length;

			if (m_length > 0)
			{
				m_data = new byte[m_length];
				CUtils.memcpy(m_data, 0, msg.m_data, 0, m_length);
			}
		}

		public CMessage clone()
		{
			CMessage clone = new CMessage();
			clone.copyFrom(this);
			return clone;
		}

		public int consume(byte[] buffer, int offsetStart, int offsetEnd)
		{
			int count = offsetEnd - offsetStart + 1;
			
			if (m_offset >= 8)
			{
				int k = Math.Min(m_length + 8 - m_offset, count);
				CUtils.memcpy(m_data, m_offset - 8, buffer, offsetStart, k);

				m_offset += k;
				return k;
			}

			if (m_offset < 4)
			{
				int k = Math.Min(4 - m_offset, count);
				for (int x = m_offset; x < m_offset + k; x++)
				{
					m_type = CUtils.setByte(m_type, x, buffer[offsetStart + x - m_offset]);
				}

				m_offset += k;
				return k;
			}

			int t = Math.Min(8 - m_offset, count);
			for (int x = m_offset; x < m_offset + t; x++)
			{
				m_length = CUtils.setByte(m_length, x - 4, buffer[offsetStart + x - m_offset]);
			}

			m_offset += t;

			if (m_offset >= 8 && m_length > 0 && m_data == null)
			{
				m_data = new byte[m_length];
			}

			return t;
		}

		public bool isFull()
		{
			return m_offset >= 8 && m_offset == 8 + m_length;
		}

		//==============================================================================================

		public int getType()
		{
			return m_type;
		}

		public EMessageType getTypeEnum()
		{
			return (EMessageType)getType();
		}

		public int getLength()
		{
			return m_length;
		}

		public byte[] getData()
		{
			return m_data;
		}

		//==============================================================================================

		private int m_offset;

		private int m_type;
		private int m_length;
		private byte[] m_data;
	}

}
