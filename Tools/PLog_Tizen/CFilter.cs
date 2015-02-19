using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace PLog
{
	public class CFilter
	{
		private String m_name;
		private String m_tag;
		private int m_pid;

		public CFilter(String name, String tag, int pid)
		{
			setName(name);
			setTag(tag);
			setPid(pid);
		}

		public String getName() { return m_name; }
		public String getTag() { return m_tag; }
		public int getPid() { return m_pid; }

		public void setName(String name) { m_name = name; }
		public void setTag(String tag) { m_tag = tag; }
		public void setPid(int pid) { m_pid = pid; }
	}
}
