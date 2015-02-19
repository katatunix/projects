using System;
using Microsoft.VisualStudio.TestTools.UnitTesting;

using CELib.ExpressionImpl;

namespace CETest
{
	[TestClass]
	public class TerminalExpressionTest
	{
		[TestMethod]
		public void TestInterpret()
		{
			TerminalExpression exp = new TerminalExpression("x");
			Assert.AreEqual(7.0, exp.interpret("x=7.0,y=100.0"), 0.000001);

			exp = new TerminalExpression("yyy");
			Assert.AreEqual(100.0, exp.interpret("x=7.0,yyy=100.0"), 0.000001);

			exp = new TerminalExpression("yyy");
			Exception e = null;
			try
			{
				exp.interpret("x=7.0,yy=100.0");
			}
			catch (Exception ex) { e = ex; }
			Assert.IsNotNull(e);

			exp = new TerminalExpression("yyy");
			e = null;
			try
			{
				exp.interpret("");
			}
			catch (Exception ex) { e = ex; }
			Assert.IsNotNull(e);
		}
	}
}
