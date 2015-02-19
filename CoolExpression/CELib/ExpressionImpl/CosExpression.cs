using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CELib.ExpressionImpl
{
	class CosExpression : Expression
	{
		public CosExpression(Expression e)
		{
			m_expr = e;
		}

		public override double interpret(string context)
		{
			return Math.Cos( m_expr.interpret(context) );
		}

		private Expression m_expr;
	}
}
