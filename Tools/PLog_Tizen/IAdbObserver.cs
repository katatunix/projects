using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace PLog
{
	interface IAdbObserver
	{
		void onReceive(String log, int sessionId);
		void onComplete();
	}
}
