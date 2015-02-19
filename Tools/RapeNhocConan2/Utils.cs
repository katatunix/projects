using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;
using System.Drawing;
using System.IO;

namespace RapeNhocConan2
{
	class ProxySettings
	{
		public String domain;
		public int port;
		public String username;
		public String password;
	}

	class Utils
	{
		private static WebProxy myProxy = null;
		private static WebClient webClient = new WebClient();

		public static void Init()
		{
			webClient.Proxy = null;
		}

		public static void SetProxy(String host, int port, String username, String password)
		{
			try
			{
				myProxy = new WebProxy(host, port);
				myProxy.Credentials = new NetworkCredential(username, password);
			}
			catch (Exception)
			{
				myProxy = null;
			}
			webClient.Proxy = myProxy;
		}

		public static void SetProxy(WebProxy p)
		{
			myProxy = p;
		}

		public static String GetHTML(String url)
		{
			try
			{
				HttpWebRequest request = (HttpWebRequest) WebRequest.Create(url);

				request.Proxy = myProxy;

				HttpWebResponse response = (HttpWebResponse) request.GetResponse();

				// Get the stream associated with the response.
				Stream receiveStream = response.GetResponseStream();

				// Pipes the stream to a higher level stream reader with the required encoding format. 
				StreamReader readStream = new StreamReader(receiveStream, Encoding.UTF8);

				String res = readStream.ReadToEnd();

				readStream.Close();
				receiveStream.Close();
				response.Close();

				return res.Trim();
			}
			catch (Exception)
			{
				return null;
			}
		}

		public static String PostHTML(String url, String[] paraList, String[] valueList)
		{
			HttpWebRequest request = (HttpWebRequest)WebRequest.Create(url);

			request.Proxy = myProxy;

			request.Method = "POST";

			String postData = "";
			int paraLen = paraList.Length;

			for (int i = 0; i < paraLen; i++)
			{
				if (postData.Length > 0)
				{
					postData += "&";
				}
				postData += paraList[i] + "=" + valueList[i];
			}

			ASCIIEncoding encoding = new ASCIIEncoding();
			byte[] byte1 = encoding.GetBytes(postData);

			// Set the content type of the data being posted.
			request.ContentType = "application/x-www-form-urlencoded";

			// Set the content length of the string being posted.
			request.ContentLength = byte1.Length;

			Stream requestStream = request.GetRequestStream();

			requestStream.Write(byte1, 0, byte1.Length);

			// Close the Stream object.
			requestStream.Close();

			HttpWebResponse response = (HttpWebResponse)request.GetResponse();

			// Get the stream associated with the response.
			Stream receiveStream = response.GetResponseStream();

			// Pipes the stream to a higher level stream reader with the required encoding format. 
			StreamReader readStream = new StreamReader(receiveStream, Encoding.UTF8);

			String html = readStream.ReadToEnd();
			readStream.Close();
			receiveStream.Close();
			response.Close();

			return html;
		}

		public static void DownloadFile(String url, String savePath)
		{
			webClient.DownloadFile(url, savePath);
		}

		public static Bitmap GetBitmap(String url)
		{
			try
			{
				Stream stream = webClient.OpenRead(url);
				Bitmap bitmap = new Bitmap(stream);
				stream.Flush();
				stream.Close();
				return bitmap;
			}
			catch (Exception)
			{
				return null;
			}
		}

		public static String AddZero(int i)
		{
			String s = i.ToString();
			while (s.Length < 4)
			{
				s = "0" + s;
			}
			return s;
		}

		public static String GetExt(String path)
		{
			int i = path.LastIndexOf(".");
			if (i == -1) return "";
			return path.Substring(i + 1);
		}

		public static ProxySettings LoadProxy(String proxyPath)
		{
			try
			{
				StreamReader sr = new StreamReader(proxyPath);

				if (sr.ReadLine() == "0")
				{
					sr.Close();
					return null;
				}

				ProxySettings ps = new ProxySettings();

				ps.domain = sr.ReadLine();
				ps.port = int.Parse(sr.ReadLine());
				ps.username = sr.ReadLine();
				ps.password = sr.ReadLine();
				sr.Close();

				return ps;
			}
			catch (Exception)
			{
				return null;
			}
		}

		public static void SaveProxy(ProxySettings ps, String proxyPath)
		{
			try
			{
				StreamWriter sr = new StreamWriter(proxyPath);

				if (ps == null)
				{
					sr.WriteLine("0");
				}
				else
				{
					sr.WriteLine("1");
					sr.WriteLine(ps.domain);
					sr.WriteLine(ps.port.ToString());
					sr.WriteLine(ps.username);
					sr.WriteLine(ps.password);
				}
				sr.Close();
			}
			catch (Exception)
			{
				
			}
		}

		public static String LoadLocation()
		{
			try
			{
				StreamReader sr = new StreamReader(NhocConan.CONFIG_FILE);
				String location = sr.ReadLine();
				sr.Close();
				return location;
			}
			catch (Exception)
			{
				return null;
			}
		}

		public static void SaveLocation(String location)
		{
			try
			{
				StreamWriter sw = new StreamWriter(NhocConan.CONFIG_FILE);
				sw.WriteLine(location);
				sw.Close();
			}
			catch (Exception)
			{
			}
		}

		public static String TrimPath(String path)
		{
			int j = -1;
			for (int i = path.Length - 1; i >= 0; i--)
			{
				if (path[i] != '\\')
				{
					j = i;
					break;
				}
			}
			if (j == -1) return "";

			if (j == path.Length - 1) return path;
			return path.Substring(0, j + 1);

		}
	}
}
