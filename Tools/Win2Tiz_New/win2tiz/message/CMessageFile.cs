using System;
using System.IO;

using win2tiz.utils;

namespace win2tiz.message
{
	/// <summary>
	/// Depend on:
	/// EMessageType
	/// CMessage
	/// CBinStream
	/// CUtils
	/// </summary>
	class CMessageFile
	{
		public CMessageFile(CMessage msg)
		{
			byte[] data = msg.getData();
			CBinStream stream = new CBinStream(data);
			int filePathLen = stream.readInt();
			m_filePath = stream.readString(filePathLen); // sure the path is normalized
			m_fileSize = stream.remainBytes();
			m_offset = stream.currentPosition();
			m_data = data;

			stream.close();
		}

		public string getFilePath()
		{
			return m_filePath;
		}

		public int getOffset()
		{
			return m_offset;
		}

		public int getFileSize()
		{
			return m_fileSize;
		}

		public byte[] getData()
		{
			return m_data;
		}

		public static CMessage createMessage(string path)
		{
			try
			{
				using (BinaryReader reader = new BinaryReader(File.OpenRead(path)))
				{
					int fileSize = (int)reader.BaseStream.Length;
					//if (fileSize <= 0) return null;
					
					int msgLen = 4 + path.Length + fileSize;
					
					byte[] data = new byte[msgLen];
					CUtils.int2bytes(path.Length, data);
					CUtils.getBytesFromString(path, data, 4);

					if (fileSize > 0)
					{
						reader.Read(data, 4 + path.Length, fileSize);
					}

					return new CMessage((int)EMessageType.eFile, msgLen, data);
				}
			}
			catch (Exception)
			{
				return null;
			}
		}

		private string m_filePath;
		private int m_fileSize;
		private byte[] m_data;
		private int m_offset;
	}
}
