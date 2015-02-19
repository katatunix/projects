using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace RapeNhocConan2
{
	interface IDownloadNotifier
	{
		void OnProgress(int handle, int percent);
	}
}
