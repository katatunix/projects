using System;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using CELib;

namespace CETest
{
	[TestClass]
	public class CalculatorTest
	{
		[TestInitialize]
		public void init()
		{
			
		}

		[TestMethod]
		public void TestCalculate()
		{
			Calculator calculator;
			double x, y;
			
			//-----
			calculator = new Calculator("(x+y) * (sin(x)+cos(y))");

			x = 1; y = 2;
			Assert.AreEqual(
				(x + y) * ( Math.Sin(x) + Math.Cos(y) ),
				calculator.calculate("x=1,y=2")
			);
			x = 7.82; y = 56.79;
			Assert.AreEqual(
				(x + y) * ( Math.Sin(x) + Math.Cos(y) ),
				calculator.calculate("x=7.82,y=56.79")
			);

			//-----
			calculator = new Calculator("8.88*(x+y) * (sin(x)+cos(y))/tan(100)");
			x = 10; y = 20;
			Assert.AreEqual(
				8.88 * (x + y) * ( Math.Sin(x) + Math.Cos(y) )/Math.Tan(100),
				calculator.calculate("x=10;y=20")
			);
		}

		[TestMethod]
		public void TestCalculateWithException()
		{
			Exception ex;
			
			ex = null;
			try
			{
				Calculator calculator = new Calculator("(x+y) * (sin(x)+cos(y)))))");
			}
			catch (Exception e) { ex = e; }
			Assert.IsNotNull(ex);


			ex = null;
			try
			{
				Calculator calculator = new Calculator("x+y");
				calculator.calculate("x=100;yy=7777");
			}
			catch (Exception e) { ex = e; }
			Assert.IsNotNull(ex);

			ex = null;
			try
			{
				Calculator calculator = new Calculator("x+y");
				calculator.calculate("x=100;yy=7=y=777");
			}
			catch (Exception e) { ex = e; }
			Assert.IsNotNull(ex);
		}
	}
}
