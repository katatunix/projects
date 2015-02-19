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
			

			richTextBox1.Text = CConfig.getExceptionString();
		}

		
	}
}
