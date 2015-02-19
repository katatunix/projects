using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

using System.IO;

namespace CopyAp5SoundFiles
{
	public partial class FormMain : Form
	{
		public FormMain()
		{
			InitializeComponent();
		}

		private void buttonCopy_Click(object sender, EventArgs e)
		{
			richTextBoxResult.Text = "";

			string fromDir = textBoxFrom.Text;
			string toDir = textBoxTo.Text;
			if (!Directory.Exists(fromDir))
			{
				MessageBox.Show("[From Directory] is not existed!", "Error",
					MessageBoxButtons.OK, MessageBoxIcon.Error);
				return;
			}
			if (!Directory.Exists(toDir))
			{
				MessageBox.Show("[To Directory] is not existed!", "Error",
					MessageBoxButtons.OK, MessageBoxIcon.Error);
				return;
			}

			ProcessCopy(fromDir, toDir);
		}

		private void ProcessCopy(string fromDir, string toDir)
		{
			richTextBoxResult.Text = "Please wait...";

			string[] filePathsListFrom = Directory.GetFiles(fromDir, "*.*", SearchOption.AllDirectories);
			string[] filePathsListTo = Directory.GetFiles(toDir, "*.*", SearchOption.AllDirectories);
			
			string[] fileNamesListFrom = new string[filePathsListFrom.Length];
			string[] fileNamesListTo = new string[filePathsListTo.Length];

			for (int i = 0; i < filePathsListFrom.Length; i++)
			{
				fileNamesListFrom[i] = GetFileName(filePathsListFrom[i]);
			}

			for (int i = 0; i < filePathsListTo.Length; i++)
			{
				fileNamesListTo[i] = GetFileName(filePathsListTo[i]);
			}

			string log = "";
			for (int i = 0; i < fileNamesListFrom.Length; i++)
			{
				for (int j = 0; j < fileNamesListTo.Length; j++)
				{
					if (fileNamesListTo[j] == fileNamesListFrom[i])
					{
						File.Copy(filePathsListFrom[i], filePathsListTo[j], true);
						log += "[" + filePathsListFrom[i] + "] >>> [" + filePathsListTo[j] + "]\n";
					}
				}
			}

			richTextBoxResult.Text = log;
		}

		private string GetFileName(string path)
		{
			int pos = path.LastIndexOf('\\');
			return path.Substring(pos + 1);
		}

		private void FormMain_Load(object sender, EventArgs e)
		{
		}
	}
}
