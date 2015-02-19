using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Management;
using System.Text;
using win2tiz.visualc;

namespace Test
{
	class Program
	{
		static void Main(string[] args)
		{
			var myId = Process.GetCurrentProcess().Id;
			var query = string.Format("SELECT ParentProcessId FROM Win32_Process WHERE ProcessId = {0}", myId);
			var search = new ManagementObjectSearcher("root\\CIMV2", query);
			var results = search.Get().GetEnumerator();
			if (!results.MoveNext()) throw new Exception("Huh?");
			var queryObj = results.Current;
			uint parentId = (uint)queryObj["ParentProcessId"];
			var parent = Process.GetProcessById((int)parentId);
			Console.WriteLine("I was started by [{0}] {1}", parentId, parent.ProcessName);
			

			
		}
	}
}
