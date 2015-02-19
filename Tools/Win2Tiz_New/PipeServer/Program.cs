using System;
using System.IO;
using System.IO.Pipes;
using System.Text;
using System.Threading;

namespace PipeServer
{
	class Program
	{
		static void Main(string[] args)
		{
			Console.WriteLine("SERVER");

			NamedPipeServerStream pipeServer = new NamedPipeServerStream("testpipe", PipeDirection.InOut, 1);

			Console.WriteLine("Wait for a client to connect");
			// Wait for a client to connect
			pipeServer.WaitForConnection();

			Console.WriteLine("Client connected");

			byte[] buffer = new byte[1];
			
			pipeServer.Read(buffer, 0, 1);

			Console.WriteLine("Receive: " + buffer[0]);

			pipeServer.Close();

			Console.WriteLine("........");
			Console.ReadLine();
		}
	}
}
