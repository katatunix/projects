using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.CompilerServices;
using System.Text;
using System.Threading.Tasks;

[assembly: InternalsVisibleToAttribute("CETest")]

namespace CELib.ExpressionImpl
{
	class TerminalExpression : Expression
	{
		public TerminalExpression(string var)
		{
			m_var = var;
		}

		public override double interpret(string context)
		{
			if (!string.IsNullOrEmpty(context))
			{
				string[] p = context.Split(s_separator, StringSplitOptions.RemoveEmptyEntries);
				for (int i = 0; i < p.Length; i++)
				{
					int index = p[i].IndexOf(s_equal);
					if (index == -1) continue;
					int index2 = p[i].LastIndexOf(s_equal);
					if (index != index2) continue;

					string var = p[i].Substring(0, index).Trim();
					if (var != m_var) continue;
					string val = p[i].Substring(index + 1);
					return double.Parse( val );
				}
			}

			// Now we expect m_var is a constant
			return double.Parse(m_var);
		}

		private string m_var;

		private static readonly char[] s_separator = new char[] { ',', ';' };
		private static readonly char s_equal = '=';
	}
}
