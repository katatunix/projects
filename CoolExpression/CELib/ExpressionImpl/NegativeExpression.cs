using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CELib.ExpressionImpl
{
	class NegativeExpression : Expression
	{
		public NegativeExpression(Expression e)
		{
			m_expr = e;
		}

		public override double interpret(string context)
		{
			return -m_expr.interpret(context);
		}

		private Expression m_expr;
	}
}
