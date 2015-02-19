namespace win2tiz.message
{
	/// <summary>
	/// Depend on:
	/// CMessage
	/// EMessageType
	/// </summary>
	class CMessageFreeNumRequest
	{
		public CMessageFreeNumRequest(CMessage msg)
		{
			
		}

		public static CMessage createMessage()
		{
			return new CMessage((int)EMessageType.eFreeNumRequest, 0, null);
		}

	}
}
