using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Threading;
using System.Net;
using System.IO;

namespace RapeNhocConan2
{
	public partial class FormMain : Form
	{
		private static int MAX_MANGAN_NUMBER = 1000;
		private int m_MangaNumber;
		private Manga[] m_MangaList;

		public FormMain()
		{
			InitializeComponent();

			m_MangaNumber = 0;
			m_MangaList = new Manga[MAX_MANGAN_NUMBER];
			for (int i = 0; i < MAX_MANGAN_NUMBER; i++)
			{
				m_MangaList[i] = new Manga();
			}
		}

		private void FormMain_Load(object sender, EventArgs e)
		{
			System.Net.ServicePointManager.Expect100Continue = false;
			Utils.Init();
			ProxySettings ps = Utils.LoadProxy(NhocConan.PROXY_FILE);
			if (ps != null)
			{
				Utils.SetProxy(ps.domain, ps.port, ps.username, ps.password);
			}

			String location = Utils.LoadLocation();
			if (location != null)
			{
				NhocConan.SAVE_LOCATION = location;
			}
			if (!Directory.Exists(NhocConan.SAVE_LOCATION))
			{
				Directory.CreateDirectory(NhocConan.SAVE_LOCATION);
			}
			folderBrowserDialogSelectLocation.SelectedPath = NhocConan.SAVE_LOCATION;
		}

		private void buttonManga_Click(object sender, EventArgs e)
		{
			String[] a = NhocConan.ObtainMangaNameList();
			if (a == null)
			{
				ShowConnectionError();
				return;
			}


			m_MangaNumber = a.Length;
			for (int i = 0; i < m_MangaNumber; i++)
			{
				m_MangaList[i].m_Name = a[i];
				m_MangaList[i].m_ChapterNumber = 0;
			}

			listBoxManga.Items.Clear();
			listBoxManga.Items.AddRange(a);

			listBoxManga.SelectedIndex = -1;

			listBoxChapter.Items.Clear();
			listBoxChapter.SelectedIndex = -1;
		}

		private void listBoxManga_SelectedIndexChanged(object sender, EventArgs e)
		{
			ProcessCurMangan(false);
		}

		private void buttonSelectAll_Click(object sender, EventArgs e)
		{
			for (int i = 0; i < listBoxChapter.Items.Count; i++)
			{
				listBoxChapter.SetSelected(i, true);
			}
		}

		private void buttonUnselect_Click(object sender, EventArgs e)
		{
			listBoxChapter.SelectedIndex = -1;
		}

		private void buttonChapter_Click(object sender, EventArgs e)
		{
			ProcessCurMangan(true);
		}

		private void ProcessCurMangan(bool isForceObtain)
		{
			int i = listBoxManga.SelectedIndex;
			if (i < 0 || i >= m_MangaNumber)
			{
				MessageBox.Show("Please select a mangan!", "Error",
					MessageBoxButtons.OK, MessageBoxIcon.Error);
				return;
			}


			Manga curManga = m_MangaList[i];

			if (curManga.m_ChapterNumber == 0 || isForceObtain)
			{
				String[] a = NhocConan.ObtainChapterNameList(curManga.m_Name);
				if (a == null)
				{
					ShowConnectionError();
					return;
				}
				curManga.m_ChapterNumber = a.Length;
				for (int j = 0; j < a.Length; j++)
				{
					curManga.m_ChapterNameList[j] = a[j];
				}
			}

			listBoxChapter.Items.Clear();
			for (int j = 0; j < curManga.m_ChapterNumber; j++)
			{
				listBoxChapter.Items.Add(curManga.m_ChapterNameList[j]);
			}
		}

		private void buttonProcess_Click(object sender, EventArgs e)
		{
			int curMangaIndex = listBoxManga.SelectedIndex;
			if (curMangaIndex < 0 || curMangaIndex >= m_MangaNumber) return;

			int processChapterNumber = listBoxChapter.SelectedIndices.Count;
			if (processChapterNumber <= 0) return;

			String[] processChapterNameList = new String[processChapterNumber];

			Manga curManga = m_MangaList[curMangaIndex];

			for (int i = 0; i < processChapterNumber; i++)
			{
				int j = listBoxChapter.SelectedIndices[i];
				processChapterNameList[i] = curManga.m_ChapterNameList[j];
			}

			Form formProcess = new FormProcess(curManga.m_Name, processChapterNameList,
				radioButtonSavePic.Checked);
			formProcess.ShowDialog(this);
		}

		private void buttonProxy_Click(object sender, EventArgs e)
		{
			new FormProxy().ShowDialog(this);
		}

		private void buttonSelectLocation_Click(object sender, EventArgs e)
		{
			DialogResult r = folderBrowserDialogSelectLocation.ShowDialog();
			if (r != DialogResult.OK) return;
			NhocConan.SAVE_LOCATION = Utils.TrimPath( folderBrowserDialogSelectLocation.SelectedPath );
		}

		private void ShowConnectionError()
		{
			MessageBox.Show("Please check your internet connection and proxy settings!", "Error",
					MessageBoxButtons.OK, MessageBoxIcon.Error);
		}

		private void FormMain_FormClosing(object sender, FormClosingEventArgs e)
		{
			Utils.SaveLocation(NhocConan.SAVE_LOCATION);
		}

	}
}
