using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.Threading;
using System.Text.RegularExpressions;
using System.IO;

namespace kFolder
{
	public partial class kFolderFrm : Form
	{
		public iSession mySession;
		public Regex RgxUrl;
		public System.Globalization.CompareInfo cmpUrl;
		public Thread thread;

		public static String PROXY_FILE = "kFolder_Proxy.dat";

		public kFolderFrm()
		{
			InitializeComponent();
		}

		private void kFolderFrm_Load(object sender, EventArgs e)
		{
			System.Net.ServicePointManager.Expect100Continue = false;
			loadProxy();

			textBoxConfirm.Enabled = false;
			buttonFinal.Enabled = false;

			cmpUrl = System.Globalization.CultureInfo.InvariantCulture.CompareInfo;

			string pattern = @"^(http|https|ftp)\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~])*[^\.\,\)\(\s]$";
			RgxUrl = new Regex(pattern, RegexOptions.Compiled | RegexOptions.IgnoreCase);
		}

		private void MesErr()
		{
			MessageBox.Show(this, "4 digits you type are wrong OR this file is deleted!", "Error",
				MessageBoxButtons.OK, MessageBoxIcon.Error);
		}

		private void buttonFinal_Click(object sender, EventArgs e)
		{
			String number = textBoxConfirm.Text;
			if (number.Length != 4)
			{
				MesErr();
				return;
			}
			for (int i = 0; i < 4; i++)
			{
				if (number[i] < '0' || number[i] > '9')
				{
					MesErr();
					return;
				}
			}

			String s = Utils.GetFinalLink(mySession, textBoxConfirm.Text);
			if (s == null)
			{
				MesErr();
			}
			else
			{
				textBoxFinal.Text = s;
			}
		}

		public void buttonNext_Click_Async()
		{
			mySession = Utils.GetSessionFrom(textBoxOriginal.Text);
			if (mySession == null)
			{
				nextInProgress(false);
				MessageBox.Show(this, "Can not connect to Internet or the file is not existed!", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
			}
			else
			{
				pictureBox.Image = Utils.GetBitmap(@"http://ints.ifolder.ru/random/images/?session="
					+ mySession.session);
				nextInProgress(false);
			}
		}

		private bool validUrl(String url)
		{
			if ( !RgxUrl.IsMatch(url) ) return false;
			//if (url.IndexOf("ifolder.ru") != -1 || url.IndexOf("yapapka.com") != -1) return true;
			//return false;

			return true;
		}

		private void buttonNext_Click(object sender, EventArgs e)
		{
			textBoxOriginal.Text = textBoxOriginal.Text.Trim();
			String url = textBoxOriginal.Text;
			if ( !validUrl(url) )
			{
				MessageBox.Show(this, "Invalid URL!", "Error",
					MessageBoxButtons.OK, MessageBoxIcon.Error);
				return;
			}

			nextInProgress(true);
			pictureBox.Image = null;
			thread = new Thread(new ThreadStart(this.buttonNext_Click_Async));
			thread.Start();
		}

		public void nextInProgress(bool b)
		{
			progressBar.Visible = b;
			textBoxOriginal.Enabled = !b;
			textBoxConfirm.Enabled = !b;
			textBoxConfirm.Text = "";
			textBoxFinal.Text = "";

			buttonNext.Enabled = !b;
			buttonFinal.Enabled = !b;
			buttonSave.Enabled = !b;
		}

		private void kFolderFrm_FormClosed(object sender, FormClosedEventArgs e)
		{
			Environment.Exit(1);
		}

		private void buttonSave_Click(object sender, EventArgs e)
		{
			StreamWriter sw = new StreamWriter(Environment.SystemDirectory + "\\" + PROXY_FILE);
			sw.WriteLine(checkBoxUse.Checked ? "1" : "0");
			sw.WriteLine(textBoxDomain.Text);
			sw.WriteLine(textBoxPort.Text);
			sw.WriteLine(textBoxUsername.Text);
			sw.WriteLine(textBoxPassword.Text);
			sw.Close();
			Utils.SetProxy(textBoxDomain.Text, int.Parse(textBoxPort.Text),
					textBoxUsername.Text, textBoxPassword.Text);
		}

		private void loadProxy()
		{
			try
			{
				StreamReader sr = new StreamReader(Environment.SystemDirectory + "\\" + PROXY_FILE);
				
				checkBoxUse.Checked = sr.ReadLine() == "1";
				groupBoxProxy.Enabled = checkBoxUse.Checked;

				textBoxDomain.Text = sr.ReadLine();
				textBoxPort.Text = sr.ReadLine();
				textBoxUsername.Text = sr.ReadLine();
				textBoxPassword.Text = sr.ReadLine();
				sr.Close();

				if (checkBoxUse.Checked)
					Utils.SetProxy(textBoxDomain.Text, int.Parse(textBoxPort.Text),
						textBoxUsername.Text, textBoxPassword.Text);
			}
			catch (Exception)
			{
				Utils.myProxy = null;
				checkBoxUse.Checked = false;
				groupBoxProxy.Enabled = false;
			}
		}

		private void checkBoxUse_CheckedChanged(object sender, EventArgs e)
		{
			groupBoxProxy.Enabled = checkBoxUse.Checked;
		}

		private void textBoxConfirm_KeyPress(object sender, KeyPressEventArgs e)
		{
			if (e.KeyChar == (char)13)
			{
				buttonFinal_Click(null, null);
			}
		}

		private void tabPagekFolder_Enter(object sender, EventArgs e)
		{
			textBoxOriginal.Focus();
		}

		private void textBoxFinal_MouseDown(object sender, MouseEventArgs e)
		{
			if (textBoxFinal.Focused)
			{
				textBoxFinal.SelectAll();
			}
		}

		private void textBoxFinal_Enter(object sender, EventArgs e)
		{
			textBoxFinal.SelectAll();
		}
	}
}
