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
	class CMessageCompileRequest
	{
		public CMessageCompileRequest(CMessage msg)
		{
			byte[] data = msg.getData();
			CBinStream stream = new CBinStream(data);
			m_cmd = stream.readString();
			stream.close();
		}

		public string getCmd()
		{
			return m_cmd;
		}

		public static CMessage createMessage(string compileCmd)
		{
			int msgLen = compileCmd.Length;
			byte[] data = new byte[msgLen];
			CUtils.getBytesFromString(compileCmd, data);
			return new CMessage((int)EMessageType.eCompileRequest, msgLen, data);
		}

		private string m_cmd;
	}
}
