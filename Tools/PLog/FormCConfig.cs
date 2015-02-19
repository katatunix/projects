using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace PLog
{
	public partial class FormCConfig : Form
	{
		public FormCConfig()
		{
			InitializeComponent();
		}

		private void buttonOK_Click(object sender, EventArgs e)
		{
			CConfig.m_NDK = textBoxNDK.Text;
			CConfig.m_Python = textBoxPython.Text;
			CConfig.m_soLib = textBoxSoLib.Text;

			CConfig.parseExceptionList(richTextBox1.Text);

			CConfig.save();

			Utils.saveException(richTextBox1.Text);

			this.Close();
		}

		private void buttonCancel_Click(object sender, EventArgs e)
		{
			this.Close();
		}

		private void FormCConfig_Load(object sender, EventArgs e)
		{
			textBoxNDK.Text = CConfig.m_NDK;
			textBoxPython.Text = CConfig.m_Python;
			textBoxSoLib.Text = CConfig.m_soLib;

			richTextBox1.Text = CConfig.getExceptionString();
		}

		private void buttonNDK_Click(object sender, EventArgs e)
		{
			folderBrowserDialog1.SelectedPath = textBoxNDK.Text;
			DialogResult r = folderBrowserDialog1.ShowDialog();
			if (r == DialogResult.OK)
			{
				textBoxNDK.Text = folderBrowserDialog1.SelectedPath;
			}
		}

		private void buttonPython_Click(object sender, EventArgs e)
		{
			folderBrowserDialog1.SelectedPath = textBoxPython.Text;
			DialogResult r = folderBrowserDialog1.ShowDialog();
			if (r == DialogResult.OK)
			{
				textBoxPython.Text = folderBrowserDialog1.SelectedPath;
			}
		}

		private void buttonSo_Click(object sender, EventArgs e)
		{
			DialogResult r = openFileDialog1.ShowDialog();
			if (r == DialogResult.OK)
			{
				textBoxSoLib.Text = openFileDialog1.FileName;
			}
		}
	}
}
