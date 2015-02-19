using win2tiz.utils;

namespace win2tiz.message
{
	/// <summary>
	/// Depend on:
	/// CMessage
	/// EMessageType
	/// CBinStream
	/// CUtils
	/// </summary>
	class CMessageFreeNumResponse
	{
		public CMessageFreeNumResponse(CMessage msg)
		{
			byte[] data = msg.getData();
			CBinStream stream = new CBinStream(data);
			m_num = stream.readInt();
			stream.close();
		}

		public int getNum()
		{
			return m_num;
		}

		public static CMessage createMessage(int num)
		{
			int msgLen = 4;
			byte[] data = new byte[msgLen];
			CUtils.int2bytes(num, data);

			return new CMessage((int)EMessageType.eCompileResponse, msgLen, data);
		}

		private int m_num;
	}
}
