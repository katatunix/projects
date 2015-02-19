using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using System.Management;
using System.Diagnostics;
using System.Runtime.InteropServices;

using libcore;

using win2tiz.visualc;
using libmongcc;
using System.IO;

namespace test
{
	class Program
	{
		static void Main(string[] args)
		{
			string s = Path.ChangeExtension("test", "o");
			Console.WriteLine(s);

			Console.WriteLine();
			Console.ReadLine();
		}
	}
}
