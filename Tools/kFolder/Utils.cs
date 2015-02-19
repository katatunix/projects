using System;
using System.Collections.Generic;
using System.Text;
using System.Net;
using System.IO;
using System.Xml;
using System.Threading;
using System.Drawing;
using System.Windows.Forms;

namespace kFolder
{
	class Utils
	{
		private static String SESSION_URL = @"http://ints.ifolder.ru/ints/frame/?session=";

		public static WebProxy myProxy = null;

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
		}

		public static String GetHTML(String url)
		{
			try
			{
				HttpWebRequest request = (HttpWebRequest) WebRequest.Create(url);

				if (myProxy != null)
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

		public static String GetFinalLink(iSession mySession, String digits)
		{
			HttpWebRequest request = (HttpWebRequest) WebRequest.Create(SESSION_URL + mySession.session);

			if (myProxy != null)
				request.Proxy = myProxy;
			
			request.Method = "POST";

			string postData = "confirmed_number=" + digits
				+ "&session=" + mySession.session
				+ "&ints_session=" + mySession.isession
				+ "&action=1"
				+ "&" + mySession.hsession + "=1";
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

			HttpWebResponse response = (HttpWebResponse) request.GetResponse();

			// Get the stream associated with the response.
			Stream receiveStream = response.GetResponseStream();

			// Pipes the stream to a higher level stream reader with the required encoding format. 
			StreamReader readStream = new StreamReader(receiveStream, Encoding.UTF8);

			String html = readStream.ReadToEnd();

			int t = html.IndexOf(@"/download/");
			if (t != -1)
			{
				int k = t;
				while (html[t] != '\"') t--;
				while (html[k] != '\"') k++;
				return html.Substring(t + 1, k - t - 1);
			}
			return null;
		}

		public static Bitmap GetBitmap(String url)
		{
			try
			{
				HttpWebRequest request = (HttpWebRequest) WebRequest.Create(url);

				if (myProxy != null)
					request.Proxy = myProxy;

				HttpWebResponse response = (HttpWebResponse) request.GetResponse();

				// Get the stream associated with the response.
				Stream receiveStream = response.GetResponseStream();

				Bitmap bmp = new Bitmap(receiveStream);
				receiveStream.Close();
				response.Close();

				return bmp;
			}
			catch (Exception)
			{
				return null;
			}
		}

		public static iSession GetSessionFrom(String originalLink)
		{
			String html_1 = GetHTML(originalLink);
			if (html_1 == null)
			{
				return null;
			}

			// Find the link of "here" text "сюда"
			String k_key = "?ints_code=";
			int t = html_1.IndexOf(k_key);
			if (t == -1)
			{
				return null;
			}
			html_1 = html_1.Substring(0, t);
			t = html_1.LastIndexOf("http://");
			String link_1 = html_1.Substring(t) + k_key;

			html_1 = GetHTML(link_1);

			if (html_1 == null)
			{
				return null;
			}

			t = html_1.IndexOf(@"&session=");
			String html_2 = html_1.Substring(0, t);
			String html_3 = html_1.Substring(t + 9);

			t = html_2.LastIndexOf(@"href=");

			int k = html_3.IndexOf(@">");

			int len = html_2.Length - t - 5 + k;

			String link_2 = html_1.Substring(t + 5, len);

			k = html_3.IndexOf(@"&");
			String session = html_3.Substring(0, k); //==

			GetHTML(link_2);

			link_1 = SESSION_URL + session;

			html_1 = GetHTML(link_1);
			Thread.Sleep(32000);
			html_1 = GetHTML(link_1);

			t = html_1.IndexOf(@"tag.value");
			html_1 = html_1.Substring(t);
			t = html_1.IndexOf("\"");
			html_1 = html_1.Substring(t + 1);
			t = html_1.IndexOf("\"");
			String isession = html_1.Substring(0, t); //==

			String hsession = null;
			
			t = html_1.IndexOf(@"var s=");

			if (t != -1)
			{
				html_1 = html_1.Substring(t + 6).Trim().Substring(1);
				t = html_1.IndexOf(@";");
				hsession = html_1.Substring(0, t).Trim();
				t = hsession.Length;
				hsession = hsession.Substring(0, t - 1);

				t = html_1.IndexOf(@"s.substring");
				html_1 = html_1.Substring(t);
				t = html_1.IndexOf(@"(");
				k = html_1.IndexOf(@")");
				html_1 = html_1.Substring(t + 1, k - t - 1);

				int sss = 0;
				try
				{
					sss = int.Parse(html_1);
				}
				catch (Exception)
				{
					sss = 0;
				}

				hsession = hsession.Substring(sss); //==
			}
			else
			{
				t = html_1.IndexOf("var v = ");
				if (t == -1)
				{
					return null;
				}
				html_1 = html_1.Substring(t);
				t = html_1.IndexOf("name=") + 6;
				hsession = html_1.Substring(t);
				t = hsession.IndexOf("'");
				hsession = hsession.Substring(0, t);
			}
			
			return new iSession(session, isession, hsession);

		}
	}
}
