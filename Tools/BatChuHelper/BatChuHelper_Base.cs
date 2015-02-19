using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace BatChuHelper
{
	abstract class BatChuHelper_Base
	{
		public abstract List<string> solve(string key, string pattern);
		public abstract bool hasSearchFull();
	}
}
