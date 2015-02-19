using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.IO;

namespace RapeNhocConan2
{
	public partial class FormProxy : Form
	{
		public FormProxy()
		{
			InitializeComponent();
		}

		private void checkBoxUseProxy_CheckedChanged(object sender, EventArgs e)
		{
			panelProxy.Enabled = checkBoxUseProxy.Checked;
		}

		private void FormProxy_Load(object sender, EventArgs e)
		{
			ProxySettings ps = Utils.LoadProxy(NhocConan.PROXY_FILE);
			if (ps == null)
			{
				checkBoxUseProxy.Checked = false;
				panelProxy.Enabled = false;
			}
			else
			{
				textBoxDomain.Text = ps.domain;
				textBoxPort.Text = ps.port == 0 ? "" : ps.port.ToString();
				textBoxUsername.Text = ps.username;
				textBoxPassword.Text = ps.password;

				checkBoxUseProxy.Checked = true;
				panelProxy.Enabled = true;
			}
		}

		private void buttonCancel_Click(object sender, EventArgs e)
		{
			this.Close();
		}

		private void buttonOK_Click(object sender, EventArgs e)
		{
			if (checkBoxUseProxy.Checked == false)
			{
				Utils.SaveProxy(null, NhocConan.PROXY_FILE);
				Utils.SetProxy(null);
			}
			else
			{
				ProxySettings ps = new ProxySettings();
				ps.domain = textBoxDomain.Text;
				try
				{
					ps.port = int.Parse(textBoxPort.Text);
				}
				catch (Exception)
				{
					ps.port = 0;
				}
				ps.username = textBoxUsername.Text;
				ps.password = textBoxPassword.Text;

				Utils.SaveProxy(ps, NhocConan.PROXY_FILE);

				Utils.SetProxy(ps.domain, ps.port, ps.username, ps.password);
			}

			this.Close();
		}
	}
}
