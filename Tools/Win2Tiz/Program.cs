using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;

namespace Win2Tiz
{
	class Program
	{
		static int Main(string[] args)
		{
            MyConsole.writeAbout();
			
			if (args.Length == 0 || args[0] == "-h")
			{
				printUsage();
				return 0;
			}

			string inputFile = null;
			string typeOfBuild = "release";
			string projectToBuild = "all";
			string gccConfig = null;
			bool isVerbose = false;
			const int DEFAULT_JOBS_NUM = 4;
			int jobsNum = DEFAULT_JOBS_NUM;

			for (int i = 0; i < args.Length; i++)
			{
				string arg = args[i];
				if (arg == "-i")
				{
					if (i + 1 >= args.Length) break;
					inputFile = args[i + 1];
					i++;
				}
				else if (arg == "-t")
				{
					if (i + 1 >= args.Length) break;
					if (args[i + 1] != "release" && args[i + 1] != "debug") break;
					typeOfBuild = args[i + 1];
					i++;
				}
				else if (arg == "-p")
				{
					if (i + 1 >= args.Length) break;
					projectToBuild = args[i + 1];
					i++;
				}
				else if (arg == "-g")
				{
					if (i + 1 >= args.Length) break;
					gccConfig = args[i + 1];
					i++;
				}
				else if (arg == "-v")
				{
					isVerbose = true;
				}
				else if (arg == "-j")
				{
					if (i + 1 >= args.Length) break;
					try
					{
						jobsNum = int.Parse(args[i + 1]);
					}
					catch (Exception)
					{
						jobsNum = DEFAULT_JOBS_NUM;
					}
				}
			}

			if (inputFile == null)
			{
				Console.WriteLine("Error: The value for the option '-i' must be specified.");
				printUsage();
				return -1;
			}

			Win2Tiz win2Tiz = new Win2Tiz();

			win2Tiz.process(
				Path.GetFullPath(inputFile),
				projectToBuild,
				typeOfBuild == "release",
				gccConfig,
				isVerbose,
				jobsNum
			);

			//Console.WriteLine("Press any key to exit...");
			//Console.ReadLine();

			return 0;
		}

		static void printUsage()
		{
			Console.WriteLine("Usage: Win2Tiz.exe [-h] -i <str> [-t <str>] [-p <str>] [-g <str>] [-v] [-j <num>]");

			Console.WriteLine("-h      display help info.");
			Console.WriteLine("-i      input path\filename. (Ex: sln2gcc.xml)");
			Console.WriteLine("-t      type of build <release|debug>");
			Console.WriteLine("-p      <project name> to build or <all>");
			Console.WriteLine("-g      the gcc config <GccConfig> </GccConfig> choosed from sln2gcc.xml");
			Console.WriteLine("-v      verbose. Print a lot of info.");
			Console.WriteLine("-j      jobs or how many simultaneous processes.");
		}
	}
}
