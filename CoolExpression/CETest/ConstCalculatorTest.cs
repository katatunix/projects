using System;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using CELib;

namespace CETest
{
	[TestClass]
	public class ConstCalculatorTest
	{
		private ConstCalculator calculator;

		[TestInitialize]
		public void init()
		{
			calculator = new ConstCalculator();
		}

		[TestMethod]
		public void TestCalculate()
		{
			Assert.AreEqual(3.0, calculator.calculate("1+2"), 0.00001);
			Assert.AreEqual(7.0, calculator.calculate("1+2*3"), 0.00001);
			Assert.AreEqual(9.0, calculator.calculate("(1+2)*3"), 0.00001);
			Assert.AreEqual(3.0, calculator.calculate("(1+2)*(3-2)"), 0.00001);
			Assert.AreEqual(10.0, calculator.calculate("(10+20)/(3-0)"), 0.00001);

			Assert.AreEqual(330.0, calculator.calculate("( (10+20)+(3-0) ) * (100/10)"), 0.00001);
			Assert.AreEqual(900.0, calculator.calculate("( (10+20)*(3-0) ) * (100/10)"), 0.00001);
			Assert.AreEqual(8100.0, calculator.calculate("( (10+20)*(3-0) ) * (100-10)"), 0.00001);

			Assert.AreEqual(-8100.0, calculator.calculate("- ( (10+20)*(3-0) ) * (100-10)"), 0.00001);
			Assert.AreEqual(8100.0, calculator.calculate("+ ( (10+20)*(3-0) ) * (100-10)"), 0.00001);

			Assert.AreEqual(Math.Sin(0.0), calculator.calculate("sin(0.0)"), 0.00001);
			Assert.AreEqual(Math.Sin(70+777), calculator.calculate("sin(70+777)"), 0.00001);
			Assert.AreEqual(Math.Cos(3.14159 / 2), calculator.calculate("cos(3.14159 / 2)"), 0.00001);
			Assert.AreEqual(Math.Sin( 70+777+Math.Cos(100) ), calculator.calculate("sin( 70+777+cos(100) )"), 0.00001);
			Assert.AreEqual(Math.Tan(777), calculator.calculate("tan(777)"), 0.00001);

			Assert.AreEqual(Math.Sqrt(777), calculator.calculate("sqrt(777)"), 0.00001);

			Assert.AreEqual(Math.Pow(3+(1*2), (2-1)/1.4), calculator.calculate("pow(3+(1*2), (2-1)/1.4)"), 0.00001);
			Assert.AreEqual(Math.Sin(Math.Pow(30, 5)), calculator.calculate("sin(pow(30, 5))"), 0.00001);
		}

		[TestMethod]
		public void TestCalculateWithException()
		{
			Exception ex = null;

			try
			{
				calculator.calculate("1+2)))");
			}
			catch (Exception e) { ex = e; }
			Assert.IsNotNull(ex);

			ex = null;
			try
			{
				calculator.calculate("(8+99)/");
			}
			catch (Exception e) { ex = e; }
			Assert.IsNotNull(ex);

			ex = null;
			try
			{
				calculator.calculate("(8+99)*");
			}
			catch (Exception e) { ex = e; }
			Assert.IsNotNull(ex);

			ex = null;
			try
			{
				calculator.calculate("*(8+99)");
			}
			catch (Exception e) { ex = e; }
			Assert.IsNotNull(ex);

			ex = null;
			try
			{
				calculator.calculate("/(8+99)");
			}
			catch (Exception e) { ex = e; }
			Assert.IsNotNull(ex);

			ex = null;
			try
			{
				calculator.calculate("(8+99)+");
			}
			catch (Exception e) { ex = e; }
			Assert.IsNotNull(ex);

			ex = null;
			try
			{
				calculator.calculate("(8+99)-");
			}
			catch (Exception e) { ex = e; }
			Assert.IsNotNull(ex);

			ex = null;
			try
			{
				calculator.calculate("pow(8+99,2.4,45)");
			}
			catch (Exception e) { ex = e; }
			Assert.IsNotNull(ex);
		}
	}
}
