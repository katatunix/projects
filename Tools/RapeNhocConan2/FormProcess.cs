using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Threading;

namespace RapeNhocConan2
{
	public partial class FormProcess : Form, IDownloadNotifier
	{
		private String m_MangaName;
		private String[] m_ChapterNameList;

		public bool m_Stop;
		private bool m_IsSavePic;

		private Thread m_Thread = null;

		private delegate void UpdateProgessCallback(int percent, int index);

		private TextBox[] m_TextBoxList;
		private ProgressBar[] m_ProgressBarList;
		private Label[] m_LabelList;

		private static int POS_X = 12;
		private static int POS_Y = 38;
		
		private static int ITEM_DELTA_Y = 30;
		private static int ITEM_DELTA_X = 7;

		private static int TXT_WIDTH = 170;
		private static int PROGBAR_WIDTH = 130;
		private static int ITEM_HEIGHT = 22;

		public FormProcess(String mangaName, String[] chapterNameList, bool isSavePic)
		{
			InitializeComponent();

			this.Text = mangaName;
			m_MangaName = mangaName;
			m_ChapterNameList = chapterNameList;

			m_IsSavePic = isSavePic;

			int len = m_ChapterNameList.Length;
			
			m_TextBoxList = new TextBox[len];
			m_ProgressBarList = new ProgressBar[len];
			m_LabelList = new Label[len];

			for (int i = 0; i < len; i++)
			{
				m_TextBoxList[i] = new TextBox();
				m_TextBoxList[i].Location = new System.Drawing.Point(POS_X, POS_Y + i * ITEM_DELTA_Y);
				m_TextBoxList[i].Size = new System.Drawing.Size(TXT_WIDTH, ITEM_HEIGHT);
				m_TextBoxList[i].ReadOnly = true;
				m_TextBoxList[i].Font = new System.Drawing.Font("Tahoma", 8F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
				m_TextBoxList[i].Text = m_ChapterNameList[i];
				m_TextBoxList[i].Select(0, 0);
				this.Controls.Add(m_TextBoxList[i]);

				m_ProgressBarList[i] = new ProgressBar();
				m_ProgressBarList[i].Location = new System.Drawing.Point(
					POS_X + TXT_WIDTH + ITEM_DELTA_X, POS_Y + i * ITEM_DELTA_Y);
				m_ProgressBarList[i].Size = new System.Drawing.Size(PROGBAR_WIDTH, ITEM_HEIGHT - 2);
				this.Controls.Add(m_ProgressBarList[i]);

				m_LabelList[i] = new Label();
				m_LabelList[i].Location = new System.Drawing.Point(
					POS_X + TXT_WIDTH + ITEM_DELTA_X, POS_Y + i * ITEM_DELTA_Y + 2);
				m_LabelList[i].Text = "[FAILED]";
				m_LabelList[i].AutoSize = true;
				this.Controls.Add(m_LabelList[i]);
			}

			Label dummy = new Label();
			dummy.Size = new System.Drawing.Size(300, ITEM_HEIGHT);
			dummy.Location = new System.Drawing.Point(POS_X, POS_Y + len * ITEM_DELTA_Y);
			dummy.Font = new System.Drawing.Font("Tahoma", 9F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			dummy.Text = m_ChapterNameList.Length + " chapter(s)";
			this.Controls.Add(dummy);
		}

		private void FormProcess_Load(object sender, EventArgs e)
		{
			m_Thread = new Thread(new ThreadStart(this.DoProcess));
			m_Thread.Start();
		}

		private void DoProcess()
		{
			m_Stop = false;
			
			int len = m_ChapterNameList.Length;
			for (int i = 0; i < len; i++)
			{
				int j = i + 1;

				bool saveResult = NhocConan.SaveAllPage(i, m_MangaName, m_ChapterNameList[i],
					m_IsSavePic, this);

				if (!saveResult)
				{
					m_ProgressBarList[i].Visible = false;
				}

				if (m_Stop) break;
			}

			if (!m_Stop)
			{
				labelProgress.Text = "Finished!";
			}
		}

		private void FormProcess_FormClosing(object sender, FormClosingEventArgs e)
		{
			m_Stop = true;
			if (m_Thread != null)
			{
				m_Thread.Abort();
			}
		}

		private void UpdateProgress(int percent, int index)
		{
			m_ProgressBarList[index].Value = percent;
		}

		#region IDownloadNotifier Members

		public void OnProgress(int handle, int percent)
		{
			this.Invoke(
				new UpdateProgessCallback(this.UpdateProgress),
				new object[] {
					percent,
					handle
				}
			);
		}

		#endregion
	}
}
