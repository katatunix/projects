using System.Runtime.InteropServices;

namespace win2tiz
{
	class Program
	{
		static int Main(string[] args)
		{
			s_handler += new ConsoleCtrEventHandler(handler);
			SetConsoleCtrlHandler(s_handler, true);

			s_w2t = new CWin2Tiz();
			int ret = s_w2t.main(args);

			return ret;
		}

		private static CWin2Tiz s_w2t;

		//=======================================================================================
		//=======================================================================================

		[DllImport("Kernel32")]
		private static extern bool SetConsoleCtrlHandler(ConsoleCtrEventHandler handler, bool add);

		private delegate bool ConsoleCtrEventHandler(CtrlType sig);
		private static ConsoleCtrEventHandler s_handler;

		private enum CtrlType
		{
			CTRL_C_EVENT = 0,
			CTRL_BREAK_EVENT = 1,
			CTRL_CLOSE_EVENT = 2,
			CTRL_LOGOFF_EVENT = 5,
			CTRL_SHUTDOWN_EVENT = 6
		}

		private static bool handler(CtrlType sig)
		{
			switch (sig)
			{
				case CtrlType.CTRL_C_EVENT:
				case CtrlType.CTRL_LOGOFF_EVENT:
				case CtrlType.CTRL_SHUTDOWN_EVENT:
				case CtrlType.CTRL_CLOSE_EVENT:
					s_w2t.forceStop();
					return true;
				default:
					return true;
			}
		}
	}
}
