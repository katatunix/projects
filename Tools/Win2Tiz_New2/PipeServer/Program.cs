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

			NamedPipeServerStream pipeServer = new NamedPipeServerStream("testpipe", PipeDirection.InOut, 4);
			//pipeServer.ReadTimeout = 10000;

			while (true)
			{

				Console.WriteLine("Wait for a client to connect");
				// Wait for a client to connect
				pipeServer.WaitForConnection();

				Console.WriteLine("Client connected");

				byte[] buffer = new byte[128];

				

				while (true)
				{
					int r = 0;
					try
					{
						r = pipeServer.Read(buffer, 0, buffer.Length);
					}
					catch (Exception ex)
					{
						r = 0;
						Console.WriteLine("Ex: " + ex.Message);
					}

					Console.WriteLine("Receive bytes: " + r);

					if (r == 0)
					{
						break;
					}
				}
			}

			pipeServer.Close();

			Console.WriteLine("FINISHED........");
			Console.ReadLine();
		}
	}
}
