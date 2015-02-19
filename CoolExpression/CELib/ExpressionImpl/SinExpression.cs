using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CELib.ExpressionImpl
{
	class SinExpression : Expression
	{
		public SinExpression(Expression e)
		{
			m_expr = e;
		}

		public override double interpret(string context)
		{
			return Math.Sin( m_expr.interpret(context) );
		}

		private Expression m_expr;
	}
}
