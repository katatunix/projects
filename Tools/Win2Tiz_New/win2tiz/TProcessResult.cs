namespace win2tiz
{
	struct TProcessResult
	{
		public bool		wasExec;
		public bool		usedMongcc;
		public string	mongccServerName;
		public int		exitCode;
		public string	outputText;
		public int		timestamp;

		public TProcessResult(bool _wasExec, bool _usedMongcc, string _mongccServerName, int _exitCode, string _outputText)
		{
			wasExec				= _wasExec;
			usedMongcc			= _usedMongcc;
			mongccServerName	= _mongccServerName;
			exitCode			= _exitCode;
			outputText			= _outputText;
			timestamp			= 0;
		}
	}
}
