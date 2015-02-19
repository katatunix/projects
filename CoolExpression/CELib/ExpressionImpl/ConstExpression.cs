using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CELib.ExpressionImpl
{
	class ConstExpression : Expression
	{
		public ConstExpression(double v)
		{
			m_v = v;
		}

		public override double interpret(string context)
		{
			return m_v;
		}

		private double m_v;
	}
}
