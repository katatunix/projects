using System;
using System.Collections.Generic;
using System.Text;

namespace kFolder
{
	public class iSession
	{
		public iSession(String session, String isession, String hsession)
		{
			this.session = session;
			this.isession = isession;
			this.hsession = hsession;
		}

		public String session;
		public String isession;
		public String hsession;

	}
}
