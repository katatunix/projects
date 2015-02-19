using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Win2Tiz
{
	enum IgnoreItemType
	{
		FILE,
		FILTER
	}
	class IgnoreItem
	{
		public IgnoreItemType m_type;
		public string m_name;
	}
}
