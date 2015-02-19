﻿using win2tiz.utils;

namespace win2tiz.message
{
	/// <summary>
	/// Depend on:
	/// EMessageType
	/// CMessage
	/// CBinStream
	/// CUtils
	/// </summary>
	class CMessageCompileResponse
	{
		public CMessageCompileResponse(CMessage msg)
		{
			byte[] data = msg.getData();
			CBinStream stream = new CBinStream(data);

			m_wasExec = stream.readByte() == k1;
			m_exitCode = stream.readInt();
			
			int outputTextLen = stream.readInt();
			m_outputText = outputTextLen > 0 ? stream.readString(outputTextLen) : "";
			
			m_oFileData = data;
			m_oFileOffset = stream.currentPosition();
			m_oFileSize = stream.remainBytes();

			stream.close();
		}

		public bool getWasExec()
		{
			return m_wasExec;
		}

		public int getExitCode()
		{
			return m_exitCode;
		}

		public string getOutputText()
		{
			return m_outputText;
		}

		public byte[] getOFileData()
		{
			return m_oFileData;
		}

		public int getOFileOffset()
		{
			return m_oFileOffset;
		}

		public int getOFileSize()
		{
			return m_oFileSize;
		}

		public static CMessage createMessage(bool wasExec, int exitCode, string outputText,
			byte[] oFileData, int oFileSize)
		{
			int msgLen = 1 + 4 + 4 + outputText.Length + oFileSize;

			byte[] data = new byte[msgLen];
			int offset = 0;

			data[offset] = wasExec ? k1 : k0;
			offset += 1;

			CUtils.int2bytes(exitCode, data, offset);
			offset += 4;

			CUtils.int2bytes(outputText.Length, data, offset);
			offset += 4;

			if (outputText.Length > 0)
			{
				CUtils.getBytesFromString(outputText, data, offset);
				offset += outputText.Length;
			}

			if (oFileData != null && oFileSize > 0)
			{
				CUtils.memcpy(data, offset, oFileData, 0, oFileSize);
			}

			offset += oFileSize;

			return new CMessage((int)EMessageType.eCompileResponse, msgLen, data);
		}

		private const byte k0 = (byte)0;
		private const byte k1 = (byte)1;

		private bool	m_wasExec;
		private int		m_exitCode;
		private string	m_outputText;
		private byte[]	m_oFileData;
		private int		m_oFileOffset;
		private int		m_oFileSize;
	}
}
