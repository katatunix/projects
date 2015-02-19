using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CELib
{
	public class Parser
	{
		public const int ERROR		= -2;
		public const int NOT_FOUND	= -1;

		public Parser(string expression)
		{
			m_expression = expression.Trim();
			m_len = m_expression.Length;
		}

		public int findIndexOfMainOperator()
		{
			int index = lookFor('+', '-');
			if (index == ERROR) return ERROR;
			
			if (index == 0) return NOT_FOUND; // first char is +- means not found separator
			if (m_len >= 2 && index == m_len - 1) return ERROR; // last char is +- means an error
			
			if (index != NOT_FOUND) return index;

			index = lookFor('*', '/');
			if (index == 0 || index == m_len - 1) return ERROR; // either first or last char is */ means an error

			return index;
		}

		public List<string> split(char s)
		{
			int count = 0;
			int last = -1;
			List<string> list = new List<string>();

			for (int i = 0; i < m_len; i++)
			{
				char c = m_expression[i];
				if (c == '(')
					count++;
				else if (c == ')')
					count--;
				else if ( c == s && count == 0 )
				{
					if (last + 1 == i) return null;
					list.Add( m_expression.Substring(last + 1, i - last - 1).Trim() );
					last = i;
				}
			}

			if (last == m_len - 1) return null;
			list.Add( m_expression.Substring(last + 1).Trim() );

			return list;
		}

		//=======================================================================================

		private int lookFor(char c1, char c2)
		{
			int count = 0;
			for (int i = m_len - 1; i >= 0; i--)
			{
				char c = m_expression[i];
				if (c == ')')
					count++;
				else if (c == '(')
					count--;
				else if ( (c == c1 || c == c2) && count == 0 )
					return i;
			}
			return count == 0 ? NOT_FOUND : ERROR;
		}

		//=======================================================================================

		private string m_expression;
		private int m_len;
	}
}
