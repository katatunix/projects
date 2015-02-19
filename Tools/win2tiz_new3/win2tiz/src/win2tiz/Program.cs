﻿using System.Runtime.InteropServices;
using System.Threading;

namespace win2tiz
{
	class Program
	{
		static int Main(string[] args)
		{
			s_handler += new ConsoleCtrEventHandler(handler);
			SetConsoleCtrlHandler(s_handler, true);

			s_w2t = new Win2Tiz();
			int ret = s_w2t.main(args);

			s_waitHandle.Set();

			return ret;
		}

		private static Win2Tiz s_w2t;

		//=======================================================================================
		//=======================================================================================

		private static AutoResetEvent s_waitHandle = new AutoResetEvent(false);

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
					s_w2t.signalToStop();
					s_waitHandle.WaitOne();
					return true;
				default:
					return true;
			}
		}
	}
}
