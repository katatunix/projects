using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CELib
{
	class ConstCalculator
	{
		public double calculate(string expresion)
		{
			return new Calculator(expresion).calculate(null);
		}
	}
}
