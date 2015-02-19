using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Xml;
using System.Threading;
using System.IO;
using System.Collections;
using System.Net;

namespace RapeNhocConan2
{
	class NhocConan
	{
		public static String HOME_FOLDER_NAME = "\\RapeNhocConan\\";
		public static String SAVE_LOCATION = Environment.GetFolderPath(Environment.SpecialFolder.Personal);
		public static String ROOT_PAGE = "http://viewer.nhocconan.com/read_chapter.php";

		public static String PROXY_FILE = Environment.SystemDirectory + "\\conan_proxy.dat";
		public static String CONFIG_FILE = Environment.SystemDirectory + "\\conan_config.dat";

		public static String[] ObtainMangaNameList()
		{
			String html = Utils.GetHTML(ROOT_PAGE);
			if (html == null) return null;

			int index = html.IndexOf("<li>");
			html = html.Substring(index);
			index = html.IndexOf("<form name");
			html = html.Substring(0, index);

			html = "<body>" + html + "</body>";

			return ProcessTagA(html);
		}

		public static String[] ObtainChapterNameList(String mangaName)
		{
			String mangaUrl = ROOT_PAGE + "/" + mangaName;
			String html = Utils.GetHTML(mangaUrl);
			if (html == null) return null;


			int index = html.IndexOf("<li>");
			if (index == -1)
			{
				return ObtainChapterNameList_v2(html);
			}
			html = html.Substring(index);
			index = html.IndexOf("<div class");
			html = html.Substring(0, index);

			html = "<body>" + html + "</body>";

			return ProcessTagA(html);
		}

		public static String[] ObtainChapterNameList_v2(String html)
		{
			int index = html.IndexOf("<div class=\"pagination\"");
			String pagination = html.Substring(index);
			index = pagination.IndexOf("</div>");
			pagination = pagination.Substring(0, index + 6);

			XmlDocument doc = new XmlDocument();
			doc.LoadXml(pagination);

			XmlNodeList nodeList = doc.GetElementsByTagName("a");

			String[] a = new String[nodeList.Count + 1];
			for (int i = 0; i < a.Length; i++)
			{
				a[i] = "###" + (i + 1);
			}

			return a;
		}

		private static string[] ProcessTagA(String html)
		{
			XmlDocument doc = new XmlDocument();
			doc.LoadXml(html);
			XmlNodeList nodeList = doc.GetElementsByTagName("a");

			String[] a = new String[nodeList.Count];
			int count = 0;
			foreach (XmlNode node in nodeList)
			{
				if (node.Attributes["class"].Value == "folder_name")
				{
					a[count] = node.InnerText;
					count++;
				}
			}

			String[] b = new String[count];
			for (int i = 0; i < count; i++)
			{
				b[i] = a[i];
			}
			return b;
		}

		public static bool SaveAllPage(int handle, String curMangaName, String curChapterName,
											bool isSavePic, IDownloadNotifier notifier)
		{
			try
			{
				String html = "";
				if (curChapterName.StartsWith("###"))
				{
					String pageIndex = curChapterName.Substring(3);
					html = Utils.PostHTML(
						ROOT_PAGE + "/" + curMangaName,
						new String[] { "page" },
						new String[] { pageIndex }
					);
				}
				else
				{
					html = Utils.GetHTML(ROOT_PAGE + "/" + curMangaName + "/" + curChapterName);
				}

				int index = html.IndexOf("<fieldset>");
				html = html.Substring(index);
				index = html.IndexOf("<div class");
				html = html.Substring(0, index);

				html = "<body>" + html + "</body>";

				XmlDocument doc = new XmlDocument();
				doc.LoadXml(html);
				XmlNodeList nodeList = doc.GetElementsByTagName("img");

				String[] a = new String[nodeList.Count];
				int count = 0;
				foreach (XmlNode node in nodeList)
				{
					a[count] = node.Attributes["src"].Value;
					count++;
				}

				String mangaFolder = SAVE_LOCATION + HOME_FOLDER_NAME + curMangaName + "\\";
				if (!Directory.Exists(mangaFolder))
				{
					Directory.CreateDirectory(mangaFolder);
				}

				if (isSavePic)
				{
					String chapterFolder = mangaFolder + curChapterName + "\\";
					if (!Directory.Exists(chapterFolder))
					{
						Directory.CreateDirectory(chapterFolder);
					}

					for (int i = 0; i < a.Length; i++)
					{
						String ext = Utils.GetExt(a[i]);
						Utils.DownloadFile(a[i], chapterFolder + Utils.AddZero(i + 1) + "." + ext);
						notifier.OnProgress(handle, (int)((float)(i + 1) / (float)a.Length * 100));
					}
				}
				else
				{
					String chapterFile = mangaFolder + curChapterName + ".txt";
					StreamWriter writer = new StreamWriter(chapterFile);
					for (int i = 0; i < a.Length; i++)
					{
						writer.WriteLine(a[i]);
						notifier.OnProgress(handle, (int)((float)(i + 1) / (float)a.Length * 100));
					}
					writer.Close();
				}

				return true;
			}
			catch (Exception e)
			{
				return false;
			}
		}
	}
}

