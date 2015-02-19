using CELib.ExpressionImpl;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CELib
{
	public class Calculator
	{
		private string m_expresion;
		private Expression m_rootExpr;

		public Calculator(string expression)
		{
			m_expresion = expression;
			m_rootExpr = makeExpressionNode(m_expresion);
		}

		public double calculate(string context)
		{
			return m_rootExpr.interpret(context);
		}

		//================================================================================================

		private Expression makeExpressionNode(string expression)
		{
			if (expression == null) throw new ArgumentException();
			expression = expression.Trim();
			int len = expression.Length;
			if (len == 0) throw new ArgumentException();

			Parser parser = new Parser(expression);
			int opeIndex = parser.findIndexOfMainOperator();
			if (opeIndex == Parser.ERROR) throw new ArgumentException();
			
			if (opeIndex != Parser.NOT_FOUND)
			{
				string expression1 = expression.Substring(0, opeIndex);
				string expression2 = expression.Substring(opeIndex + 1);
				Expression res1 = makeExpressionNode(expression1);
				Expression res2 = makeExpressionNode(expression2);

				switch (expression[opeIndex])
				{
					case '+': return createSumExpression(res1, res2);
					case '-': return createDifferenceExpression(res1, res2);
					case '*': return createProductExpression(res1, res2);
					case '/': return createQuotientExpression(res1, res2);
					default: throw new ArgumentException();
				}
			}
			
			if (expression[0] == '(' && expression[len - 1] == ')')
			{
				return makeExpressionNode( expression.Substring(1, len - 2) );
			}

			if (expression[0] == '-')
			{
				Expression e = makeExpressionNode( expression.Substring(1) );
				return createNegativeExpression(e);
			}

			if (expression[0] == '+')
			{
				return makeExpressionNode( expression.Substring(1) );
			}

			if (expression.StartsWith(SIN))
			{
				Expression e = makeExpressionNode( expression.Substring(SIN.Length) );
				return createSinExpression(e);
			}

			if (expression.StartsWith(COS))
			{
				Expression e = makeExpressionNode( expression.Substring(COS.Length) );
				return createCosExpression(e);
			}

			if (expression.StartsWith(TAN))
			{
				Expression e = makeExpressionNode( expression.Substring(TAN.Length) );
				return createTanExpression(e);
			}

			if (expression.StartsWith(SQRT))
			{
				Expression e = makeExpressionNode( expression.Substring(SQRT.Length) );
				return createSqrtExpression(e);
			}

			if (expression.StartsWith(POW))
			{
				string powContent = expression.Substring(POW.Length + 1, len - POW.Length - 2); // remove '(' and ')'
				Parser powParser = new Parser(powContent);
				List<string> subExpressions = powParser.split(',');
				if (subExpressions == null || subExpressions.Count != 2) throw new ArgumentException();

				Expression e1 = makeExpressionNode( subExpressions[0] );
				Expression e2 = makeExpressionNode( subExpressions[1] );

				return createPowExpression(e1, e2);
			}

			return createLeafExpression(expression);
		}

		//================================================================================================

		private Expression createLeafExpression(string expression)
		{
			return new TerminalExpression(expression);
		}

		private Expression createNegativeExpression(Expression e)
		{
			return new NegativeExpression(e);
		}

		private Expression createSumExpression(Expression e1, Expression e2)
		{
			return new SumExpression(e1, e2);
		}

		private Expression createDifferenceExpression(Expression e1, Expression e2)
		{
			return new DifferenceExpression(e1, e2);
		}

		private Expression createProductExpression(Expression e1, Expression e2)
		{
			return new ProductExpression(e1, e2);
		}

		private Expression createQuotientExpression(Expression e1, Expression e2)
		{
			return new QuotientExpression(e1, e2);
		}

		private Expression createSinExpression(Expression e)
		{
			return new SinExpression(e);
		}

		private Expression createCosExpression(Expression e)
		{
			return new CosExpression(e);
		}

		private Expression createTanExpression(Expression e)
		{
			return new TanExpression(e);
		}

		private Expression createSqrtExpression(Expression e)
		{
			return new SqrtExpression(e);
		}

		private Expression createPowExpression(Expression e1, Expression e2)
		{
			return new PowExpression(e1, e2);
		}

		//================================================================================================
		
		private static readonly string SIN	= "sin";
		private static readonly string COS	= "cos";
		private static readonly string TAN	= "tan";

		private static readonly string SQRT	= "sqrt";
		private static readonly string POW	= "pow";

	}
}
