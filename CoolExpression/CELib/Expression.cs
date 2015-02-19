using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CELib
{
	public abstract class Expression
	{
		public abstract double interpret(string context);
	}
}
