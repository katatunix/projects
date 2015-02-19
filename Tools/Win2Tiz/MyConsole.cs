using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Win2Tiz
{
	class MyConsole
	{

		public static void writeAbout()
		{
			Console.BackgroundColor = ConsoleColor.Black;
			Console.ForegroundColor = ConsoleColor.Cyan;
			Console.WriteLine();
			Console.WriteLine("Win2Tiz (c) nghia.buivan@gameloft.com, Spring 2013");
			Console.WriteLine();
			Console.ResetColor();
		}

		public static void writeNormal(string s)
		{
			Console.BackgroundColor = ConsoleColor.Black;
			Console.ForegroundColor = ConsoleColor.White;
			Console.Write(s);
		}

		public static void writeLineNormal(string s)
		{
			Console.BackgroundColor = ConsoleColor.Black;
			Console.ForegroundColor = ConsoleColor.White;
			Console.WriteLine(s);
		}

		public static void writeProject(string projectName)
		{
			Console.BackgroundColor = ConsoleColor.Black;
			Console.ForegroundColor = ConsoleColor.Magenta;

			Console.WriteLine();
			Console.WriteLine("============================================================");
			Console.WriteLine("Project: " + projectName);
			Console.WriteLine("============================================================");
			Console.WriteLine();

			Console.ResetColor();
		}

		public static void writeCommand(string command)
		{
			Console.BackgroundColor = ConsoleColor.Black;
			Console.ForegroundColor = ConsoleColor.Green;
			Console.Write(command);
			Console.ResetColor();
		}

		public static void writeLineCommand(string command)
		{
			Console.BackgroundColor = ConsoleColor.Black;
			Console.ForegroundColor = ConsoleColor.Green;
			Console.WriteLine(command);
			Console.ResetColor();
		}

		public static void writeError(string error)
		{
			Console.BackgroundColor = ConsoleColor.Black;
			Console.ForegroundColor = ConsoleColor.Red;
			Console.Write(error);
			Console.ResetColor();
		}

		public static void writeLineError(string error)
		{
			Console.BackgroundColor = ConsoleColor.Black;
			Console.ForegroundColor = ConsoleColor.Red;
			Console.WriteLine(error);
			Console.ResetColor();
		}

		public static void writeLineSuccess(string s)
		{
			Console.BackgroundColor = ConsoleColor.Black;
			Console.ForegroundColor = ConsoleColor.Blue;
			Console.WriteLine(s);
			Console.ResetColor();
		}

		public static void writeLineWarning(string s)
		{
			Console.BackgroundColor = ConsoleColor.Black;
			Console.ForegroundColor = ConsoleColor.Yellow;
			Console.WriteLine(s);
			Console.ResetColor();
		}

		public static void writeWarning(string s)
		{
			Console.BackgroundColor = ConsoleColor.Black;
			Console.ForegroundColor = ConsoleColor.Yellow;
			Console.Write(s);
			Console.ResetColor();
		}

		public static void writeLineTime(string s)
		{
			Console.BackgroundColor = ConsoleColor.Black;
			Console.ForegroundColor = ConsoleColor.Cyan;
			Console.WriteLine(s);
			Console.ResetColor();
		}

		public static void writeLineResult(string s)
		{
			Console.BackgroundColor = ConsoleColor.Black;

			while (true)
			{
				int[] a = new int[10];
				a[0] = s.IndexOf("error:", StringComparison.CurrentCultureIgnoreCase);
				a[1] = s.IndexOf("warning:", StringComparison.CurrentCultureIgnoreCase);
                a[2] = s.IndexOf("undefined reference to", StringComparison.CurrentCultureIgnoreCase);
				a[3] = s.IndexOf("multiple definition of", StringComparison.CurrentCultureIgnoreCase);
				const int len = 4;

				for (int i = 0; i < len; i++)
				{
					if (a[i] == -1) a[i] = int.MaxValue;
				}

				int k = a[0];
				for (int i = 1; i < len; i++)
				{
					if (a[i] < k) k = a[i];
				}

				if (k == int.MaxValue)
				{
					Console.ForegroundColor = ConsoleColor.Gray;
					Console.WriteLine(s);
					break;
				}
				else
				{
					Console.ForegroundColor = ConsoleColor.Gray;
					Console.Write(s.Substring(0, k));

					if (k == a[0])
					{
						Console.ForegroundColor = ConsoleColor.Red;
						Console.Write("error:");
						s = s.Substring(k + 6);
					}
                    else if (k == a[1])
                    {
                        Console.ForegroundColor = ConsoleColor.Yellow;
                        Console.Write("warning:");
                        s = s.Substring(k + 8);
                    }
					else if (k == a[2])
					{
						Console.ForegroundColor = ConsoleColor.Red;
						Console.Write("undefined reference to");
						s = s.Substring(k + 22);
					}
					else
					{
						Console.ForegroundColor = ConsoleColor.Red;
						Console.Write("multiple definition of");
						s = s.Substring(k + 22);
					}

					
				}
			} // while (true)

			Console.ResetColor();
		}
	}
}
