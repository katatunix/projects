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
	public partial class FormAddFilter : Form
	{
		private FormMain m_formMain;

		public FormAddFilter(FormMain formMain)
		{
			InitializeComponent();
			m_formMain = formMain;
		}

		private void buttonAddFilterOK_Click(object sender, EventArgs e)
		{
			if (textBoxName.Text.Trim().Length == 0)
			{
				labelError.Text = "[Can not be blank]";
				return;
			}


			int pid = -1;
			try
			{
				pid = int.Parse(textBoxPid.Text);
			}
			catch (Exception)
			{
				pid = -1;
			}

			CFilter f = new CFilter(textBoxName.Text, textBoxTag.Text, pid);
			
			if (m_formMain.addNewFilter(f))
			{
				this.Close();
				return;
			}

			labelError.Text = "[Can not be duplicate or too much filter]";
		}

		private void buttonCancel_Click(object sender, EventArgs e)
		{
			this.Close();
		}
	}
}
