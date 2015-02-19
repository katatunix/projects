using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.IO;
using System.Runtime.InteropServices;

namespace ReadComic
{
	public partial class MainForm : Form
	{
		private String currentHomePath = "";
		//public String dataFile = Environment.SystemDirectory + "\\ReadComic.sav";
		public String dataFile = Environment.GetFolderPath( Environment.SpecialFolder.ApplicationData ) + "\\ReadComic.sav";

		private String[] listComicPath;

		private String[] listPagePath;
		private int selectedPageIndex = -1;

		private FolderBrowserDialog fbd = new FolderBrowserDialog();

		private bool isReverse = false;

		private int originWidth, originHeight;
		private int zoomPercent = 100, zoomStep = 10;

		[DllImport("user32.dll")]
		static extern IntPtr GetFocus();

		private Control getCurrentFocusControl()
		{
			return FromChildHandle(GetFocus());
		}

		public MainForm()
		{
			InitializeComponent();
		}

		private void MainForm_Load(object sender, EventArgs e)
		{
			showPicture(null);
			fbd.Description = "Select your Manga or Comic folder:";
			int comicIndex = -1;
			int chapterIndex = -1;
			int pageIndex = -1;
			
			try
			{
				StreamReader s = new StreamReader(dataFile);
				String savePath = s.ReadLine();
				try
				{
					comicIndex = int.Parse(s.ReadLine());
					chapterIndex = int.Parse(s.ReadLine());
					pageIndex = int.Parse(s.ReadLine());
				}
				catch (Exception)
				{
					comicIndex = -1;
				}
				s.Close();
				if (Directory.Exists(savePath))
				{
					currentHomePath = savePath;
				}
				else
				{
					currentHomePath = "C:\\";
					buttonSetHome_Click(null, null);
				}
			}
			catch (Exception)
			{
				currentHomePath = "C:\\";
				comicIndex = -1;
			}
			fbd.SelectedPath = currentHomePath;
			LoadComicList();
			if (comicIndex != -1)
			{
				comboBoxComic.SelectedIndex = comicIndex;
				comboBoxChapter.SelectedIndex = chapterIndex;
				comboBoxPage.SelectedIndex = pageIndex;
			}
			pictureBoxComic.Focus();
		}

		private void buttonNext_Click(object sender, EventArgs e)
		{
			if (selectedPageIndex == -1 || listPagePath == null || listPagePath.Length == 0)
			{
				if (comboBoxChapter.SelectedIndex < comboBoxChapter.Items.Count - 1)
				{
					comboBoxChapter.SelectedIndex++;
				}
				return;
			}

			if (selectedPageIndex == listPagePath.Length - 1)
			{
				if (comboBoxChapter.SelectedIndex < comboBoxChapter.Items.Count - 1)
				{
					comboBoxChapter.SelectedIndex++;
				}
				return;
			}

			selectedPageIndex++;
			comboBoxPage.SelectedIndex = selectedPageIndex;
		}

		private void buttonPrev_Click(object sender, EventArgs e)
		{
			if (selectedPageIndex == -1 || listPagePath == null || listPagePath.Length == 0)
			{
				if (comboBoxChapter.SelectedIndex > 0)
				{
					isReverse = true;
					comboBoxChapter.SelectedIndex--;
				}
				return;
			}

			if (selectedPageIndex == 0)
			{
				if (comboBoxChapter.SelectedIndex > 0)
				{
					isReverse = true;
					comboBoxChapter.SelectedIndex--;
				}
				return;
			}

			selectedPageIndex--;
			comboBoxPage.SelectedIndex = selectedPageIndex;
		}

		private void buttonSetHome_Click(object sender, EventArgs e)
		{
			DialogResult r = fbd.ShowDialog();
			

			if (fbd.SelectedPath.Length > 0 && r == DialogResult.OK)
			{
				currentHomePath = fbd.SelectedPath;
				LoadComicList();
			}
		}

		private void showPicture(String path)
		{
			if (path == null || path.Length == 0)
			{
				pictureBoxComic.Image = null;
			}
			else
			{
				Image img = pictureBoxComic.Image = Image.FromFile(path);
				originWidth = img.Width;
				originHeight = img.Height;
				int x = (int)(1.0 * originWidth * zoomPercent / 100.0);
				int y = (int)(1.0 * originHeight * zoomPercent / 100.0);
				pictureBoxComic.Size = new Size(x, y);
			}
		}

		private void LoadComicList()
		{
			listComicPath = Directory.GetDirectories(currentHomePath);
			Array.Sort(listComicPath);
			comboBoxComic.Items.Clear();
			for (int i = 0; i < listComicPath.Length; i++)
			{
				String name = getFolderName(listComicPath[i]);
				if (name != null)
				{
					comboBoxComic.Items.Add(name);
				}
			}
			if (comboBoxComic.Items.Count > 0)
			{
				comboBoxComic.SelectedIndex = 0;
			}
		}

		private String getFolderName(String path)
		{
			int i = path.Length - 1;
			while (path[i] == '\\' && i >= 0) i--;
			if (i < 0)
			{
				return null;
			}
			
			int j = i - 1;
			while (path[j] != '\\' && j >= 0) j--;
			if (j < 0)
			{
				return null;
			}

			return path.Substring(j + 1, i - j);
		}

		private void comboBoxComic_SelectedIndexChanged(object sender, EventArgs e)
		{
			pictureBoxComic.Focus();
			int index = comboBoxComic.SelectedIndex;
			if (index < 0)
			{
				comboBoxChapter.Items.Clear();
				comboBoxChapter.SelectedItem = -1;
				comboBoxPage.Items.Clear();
				comboBoxPage.SelectedItem = -1;
				showPicture(null);
				return;
			}
			String pathComic = listComicPath[index] + "\\";
			String[] listChapter = Directory.GetDirectories(pathComic);
			Array.Sort(listChapter);
			comboBoxChapter.Items.Clear();
			for (int i = 0; i < listChapter.Length; i++)
			{
				String name = getFolderName(listChapter[i]);
				if (name != null)
				{
					comboBoxChapter.Items.Add(name);
				}
			}
			if (comboBoxChapter.Items.Count > 0)
			{
				comboBoxChapter.SelectedIndex = 0;
			}
			else
			{
				comboBoxPage.Items.Clear();
				comboBoxPage.SelectedIndex = -1;
				listPagePath = null;
				selectedPageIndex = -1;
				showPicture(null);
				buttonPrev.Enabled = false;
				buttonNext.Enabled = false;
			}
		}

		private void comboBoxChapter_SelectedIndexChanged(object sender, EventArgs e)
		{
			pictureBoxComic.Focus();
			int comicIndex = comboBoxComic.SelectedIndex;
			String chapterPath = listComicPath[comicIndex] + "\\" + comboBoxChapter.SelectedItem + "\\";
			
			String[] extensions = { "*.jpg", "*.png", "*.gif", "*.bmp" };
			String[] files = { };
			foreach (String extension in extensions)
			{
				String[] list = null;
				try
				{
					list = System.IO.Directory.GetFiles(chapterPath, extension);
				}
				catch (Exception ex)
				{
					list = null;
				}

				if (list != null)
				{
					files = files.Union(list).ToArray();
				}
			}
			
			Array.Sort(files);
			listPagePath = files;

			comboBoxPage.Items.Clear();
			comboBoxPage.SelectedIndex = -1;
			if (listPagePath.Length > 0)
			{
				for (int i = 1; i <= listPagePath.Length; i++)
				{
					comboBoxPage.Items.Add("Page " + i + " / " + listPagePath.Length);
				}
				showPicture(listPagePath[0]);

				if (isReverse)
				{
					selectedPageIndex = listPagePath.Length - 1;
					comboBoxPage.SelectedIndex = selectedPageIndex;
					isReverse = false;
				}
				else
				{
					selectedPageIndex = 0;
					comboBoxPage.SelectedIndex = 0;
				}

				buttonPrev.Enabled = selectedPageIndex > 0 ||
					comboBoxChapter.SelectedIndex > 0;
				buttonNext.Enabled = selectedPageIndex < listPagePath.Length - 1 ||
					comboBoxChapter.SelectedIndex < comboBoxChapter.Items.Count - 1;
			}
			else
			{
				isReverse = false;
				comboBoxPage.SelectedIndex = -1;
				showPicture(null);
				buttonNext.Enabled = comboBoxChapter.SelectedIndex < comboBoxChapter.Items.Count - 1;
				buttonPrev.Enabled = comboBoxChapter.SelectedIndex > 0;
			}
		}

		private void comboBoxPage_SelectedIndexChanged(object sender, EventArgs e)
		{
			pictureBoxComic.Focus();
			int pageIndex = comboBoxPage.SelectedIndex;
			if (pageIndex < 0 || pageIndex >= listPagePath.Length)
			{
				showPicture(null);
				selectedPageIndex = -1;
				return;
			}
			showPicture(listPagePath[pageIndex]);
			panelCenter_Resize(null, null);
			selectedPageIndex = pageIndex;

			buttonPrev.Enabled = true;
			buttonNext.Enabled = true;
			if (selectedPageIndex == 0 && comboBoxChapter.SelectedIndex == 0)
			{
				buttonPrev.Enabled = false;
			}
			if (selectedPageIndex == listPagePath.Length - 1 &&
				comboBoxChapter.SelectedIndex == comboBoxChapter.Items.Count - 1)
			{
				buttonNext.Enabled = false;
			}
		}

		protected override bool ProcessCmdKey(ref Message msg, Keys keyData)
		{
			pictureBoxComic.Focus();

			if (keyData == Keys.Up || keyData == Keys.Down ||
				keyData == Keys.Left || keyData == Keys.Right ||
				keyData == Keys.W || keyData == Keys.S)
			{
				if (selectedPageIndex == -1 || listPagePath == null || listPagePath.Length == 0)
				{
					if (keyData == Keys.Left)
					{
						if (comboBoxChapter.SelectedIndex > 0)
						{
							isReverse = true;
							comboBoxChapter.SelectedIndex--;
						}
					}
					else if (keyData == Keys.Right)
					{
						if (comboBoxChapter.SelectedIndex < comboBoxChapter.Items.Count - 1)
						{
							comboBoxChapter.SelectedIndex++;
						}
					}
					return true;
				}
				
				if (keyData == Keys.Left)
				{
					buttonPrev_Click(null, null);
				}
				else if (keyData == Keys.Right)
				{
					buttonNext_Click(null, null);
				}
				else if (keyData == Keys.Up || keyData == Keys.W)
				{
					pictureBoxComic.Location = new Point(
						pictureBoxComic.Location.X, pictureBoxComic.Location.Y + 15);
				}
				else if (keyData == Keys.Down || keyData == Keys.S)
				{
					pictureBoxComic.Location = new Point(
						pictureBoxComic.Location.X, pictureBoxComic.Location.Y - 15);
				}

				return true;
			}
			
			if (keyData == Keys.PageDown || keyData == Keys.PageUp || keyData == Keys.Home)
			{
				if (selectedPageIndex == -1 || listPagePath == null || listPagePath.Length == 0)
				{
					return true;
				}
				if (keyData == Keys.Home)
				{
					zoomPercent = 100;
				}
				else
				{
					zoomPercent += (keyData == Keys.PageDown ? zoomStep : -zoomStep);
				}
				int x = (int) (1.0 * originWidth * zoomPercent / 100.0);
				int y = (int) (1.0 * originHeight * zoomPercent / 100.0);
				pictureBoxComic.Size = new Size(x, y);
				panelCenter_Resize(null, null);

				return true;
			}

			if (keyData == Keys.D)
			{
				if (selectedPageIndex == -1 || listPagePath == null || listPagePath.Length == 0)
				{
					return true;
				}
				pictureBoxComic.Location = new Point(pictureBoxComic.Location.X - 15,
					pictureBoxComic.Location.Y);

				return true;
			}
			
			if (keyData == Keys.A)
			{
				if (selectedPageIndex == -1 || listPagePath == null || listPagePath.Length == 0)
				{
					return true;
				}
				pictureBoxComic.Location = new Point(pictureBoxComic.Location.X + 15,
					pictureBoxComic.Location.Y);

				return true;
			}


			//Control f = getCurrentFocusControl();
			//if (f == comboBoxComic || f == comboBoxChapter)
			//{
			//    return true;
			//}

			return base.ProcessCmdKey(ref msg, keyData);
			
		}

		private void panelCenter_Resize(object sender, EventArgs e)
		{
			Size s1 = panelCenter.Size;
			Size s2 = pictureBoxComic.Size;
			int x = (s1.Width - s2.Width) / 2;
			if (x < 0) x = 0;
			int y = (s1.Height - s2.Height) / 2;
			if (y < 0) y = 0;
			pictureBoxComic.Location = new Point(x, y);
		}

		private void MainForm_FormClosing(object sender, FormClosingEventArgs e)
		{
			try
			{
				StreamWriter s = new StreamWriter(dataFile);
				s.WriteLine(currentHomePath);
				s.WriteLine(comboBoxComic.SelectedIndex);
				s.WriteLine(comboBoxChapter.SelectedIndex);
				s.WriteLine(comboBoxPage.SelectedIndex);
				s.Close();
			}
			catch (Exception)
			{
			}
		}
		
	}
}
