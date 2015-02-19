using System;
using System.IO;
using System.IO.Pipes;
using System.Security.Principal;

namespace PipeClient
{
	class Program
	{
		static void Main(string[] args)
		{
			NamedPipeClientStream pipeClient = new NamedPipeClientStream(".", "testpipe");

            Console.WriteLine("Connecting to server...");
			try
			{
				pipeClient.Connect(2000);
			}
			catch (Exception)
			{
			}

			if (pipeClient.IsConnected)
			{
				Console.WriteLine("Connected, now send data...");

				byte[] buffer = new byte[1];
				buffer[0] = 222;

				pipeClient.Write(buffer, 0, 1);

				pipeClient.Close();
			}
			else
			{
				Console.WriteLine("Error: Could not connect!");
			}

			Console.WriteLine(".......");
			Console.ReadLine();
		}
	}
}
