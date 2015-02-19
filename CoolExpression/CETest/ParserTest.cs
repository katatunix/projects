using System;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using System.Collections.Generic;

using CELib;

namespace CETest
{
	[TestClass]
	public class ParserTest
	{
		[TestMethod]
		public void TestFind()
		{
			Parser parser = new Parser("1 + 2");
			Assert.AreEqual(2, parser.findIndexOfMainOperator());

			parser = new Parser("1+2+3+4");
			Assert.AreEqual(5, parser.findIndexOfMainOperator());

			parser = new Parser("2/3-1");
			Assert.AreEqual(3, parser.findIndexOfMainOperator());

			parser = new Parser("2*(34+15)");
			Assert.AreEqual(1, parser.findIndexOfMainOperator());

			parser = new Parser("2*(34+15))");
			Assert.AreEqual(-2, parser.findIndexOfMainOperator());

			parser = new Parser("777");
			Assert.AreEqual(-1, parser.findIndexOfMainOperator());

			parser = new Parser("(10+20)/(3-0)");
			Assert.AreEqual(7, parser.findIndexOfMainOperator());
			
		}

		[TestMethod]
		public void TestSplit()
		{
			Parser parser = new Parser("1*233, 2+999");
			List<string> list = parser.split(',');
			Assert.AreEqual(2, list.Count);
			Assert.AreEqual("1*233", list[0]);
			Assert.AreEqual("2+999", list[1]);
		}
	}
}
