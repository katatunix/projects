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
	public delegate void OnReceiveHandler(String log, int sessionId);
	public delegate void OnCompleteHandler();

	public partial class FormMain : Form, IAdbObserver
	{
		private OnReceiveHandler m_handlerReceive;
		private OnCompleteHandler m_handlerComplete;

		private bool m_onExit = false;
		
		public FormMain()
		{
			InitializeComponent();
		}

		private void FormMain_Load(object sender, EventArgs e)
		{
			CConfig.load();

			m_handlerReceive = new OnReceiveHandler(onReceiveGuiThread);
			m_handlerComplete = new OnCompleteHandler(onCompleteGuiThread);
			
			LogTabPage.init();

			foreach (CFilter filter in Utils.LoadData())
			{
				LogTabPage ltb = LogTabPage.addTabPage(filter);
				if (ltb == null) continue;
			}

			foreach (LogTabPage ltb in LogTabPage.getLogTabPageList())
			{
				if (ltb.getIsInUse())
				{
					ltb.ContextMenuStrip = this.contextMenuStripCopy;
					tabControlMain.Controls.Add(ltb);
				}
			}

			refreshDevsList();
		}

		public bool addNewFilter(CFilter filter)
		{
			LogTabPage ltb = LogTabPage.addTabPage(filter);
			if (ltb == null) return false;
			ltb.ContextMenuStrip = this.contextMenuStripCopy;
			tabControlMain.Controls.Add(ltb);
			return true;
		}

		private void FormMain_FormClosing(object sender, FormClosingEventArgs e)
		{
			m_onExit = true;

			AdbConnector.getInstance().stop();


			List<CFilter> list = new List<CFilter>();
			LogTabPage[] ltbList = LogTabPage.getLogTabPageList();
			for (int i = 1; i < ltbList.Length; i++)
			{
				LogTabPage ltb = ltbList[i];
				if (ltb.getIsInUse())
				{
					list.Add(ltb.getFilter());
				}
			}
			Utils.SaveData(list);

			CConfig.save();
		}

		private void buttonAddFilter_Click(object sender, EventArgs e)
		{
			new FormAddFilter(this).ShowDialog(this);
		}

		private void buttonRemoveFilter_Click(object sender, EventArgs e)
		{
			if (tabControlMain.SelectedIndex == 0)
			{
				MessageBox.Show(this, "Can not remove Main filter", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
				return;
			}
			TabPage tab = tabControlMain.SelectedTab;
			LogTabPage log = tab as LogTabPage;
			tabControlMain.Controls.Remove(tab);
			LogTabPage.removeTabPage(log);
		}

		private void buttonClearCurrent_Click(object sender, EventArgs e)
		{
			LogTabPage ltb = tabControlMain.SelectedTab as LogTabPage;
			ltb.clearLog();
			GC.Collect();
			GC.WaitForPendingFinalizers();
		}

		private void buttonClearAll_Click(object sender, EventArgs e)
		{
			LogTabPage.clearLogAllTabs();
			GC.Collect();
			GC.WaitForPendingFinalizers();
		}

		private void buttonGoEnd_Click(object sender, EventArgs e)
		{
			TabPage tab = tabControlMain.SelectedTab;
			LogTabPage log = tab as LogTabPage;
			log.getLogTextBox().GoEnd();
		}

		private void buttonFind_Click(object sender, EventArgs e)
		{
			TabPage tab = tabControlMain.SelectedTab;
			LogTabPage log = tab as LogTabPage;
			log.getLogTextBox().ShowFindDialog();
		}

		private void copyToolStripMenuItem_Click(object sender, EventArgs e)
		{
			TabPage tab = tabControlMain.SelectedTab;
			LogTabPage log = tab as LogTabPage;
			String s = log.getLogTextBox().SelectedText;
			if (String.IsNullOrEmpty(s)) return;
			Clipboard.SetText(s);
		}

		private void tabControlMain_SelectedIndexChanged(object sender, EventArgs e)
		{
			LogTabPage ltb = tabControlMain.SelectedTab as LogTabPage;
			ltb.setUnreadCount(0);
			ltb.refreshText();
			LogTabPage.setActiveTab(ltb);
			checkBoxWordWrap.Checked = ltb.getLogTextBox().WordWrap;
		}

		private void buttonConfig_Click(object sender, EventArgs e)
		{
			new FormCConfig().ShowDialog(this);
		}

		private void buttonGetStackStrace_Click(object sender, EventArgs e)
		{
			String logTxt = LogTabPage.getLogTabPageList()[0].getLogTextBox().Text;
			String stackTrace = StackTraceConnector.getStackTrace(logTxt);
			new FormStackTraceOutput(stackTrace).ShowDialog(this);
		}

		private void checkBoxWordWrap_CheckedChanged(object sender, EventArgs e)
		{
			LogTabPage ltb = tabControlMain.SelectedTab as LogTabPage;
			ltb.getLogTextBox().WordWrap = checkBoxWordWrap.Checked;
		}

		private void onReceiveGuiThread(String log, int sessionId)
		{
			if (m_onExit) return;

			if (AdbConnector.getInstance().getSessionId() != sessionId)
			{
				return;
			}
			LogTabPage.onReceive(log);
		}

		#region IAdbObserver Members

		public void onReceive(String log, int sessionId)
		{
			if (m_onExit) return;

			if (this.IsDisposed) return;

			if (this.InvokeRequired && !m_onExit)
			{
				try
				{
					this.Invoke(m_handlerReceive, log, sessionId);
				}
				catch (Exception)
				{
				}
			}
			else
			{
				LogTabPage.onReceive(log);
			}
		}

		public void onComplete()
		{
			if (m_onExit) return;
			if (this.InvokeRequired)
				this.Invoke(m_handlerComplete);
			else
				onCompleteGuiThread();
		}

		#endregion

		private void onCompleteGuiThread()
		{
			if (m_onExit) return;
			MessageBox.Show(this, "Disconnected, please try to connect later", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
		}

		private void buttonRefresh_Click(object sender, EventArgs e)
		{
			refreshDevsList();
		}

		private void refreshDevsList()
		{
			AdbConnector.getInstance().stop();
			LogTabPage.clearLogAllTabs();

			comboBoxDevs.Items.Clear();
			List<String> devs = AdbConnector.getDevicesList();
			if (devs == null)
			{
				MessageBox.Show(this, "Cannot connect to ADB server", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
				return;
			}

			for (int i = 0; i < devs.Count; i++)
			{
				comboBoxDevs.Items.Add(devs[i]);
			}

			if (devs.Count > 0)
			{
				comboBoxDevs.SelectedIndex = 0;
			}
		}

		private void buttonConnect_Click(object sender, EventArgs e)
		{
			if (comboBoxDevs.Items.Count <= 0)
			{
				MessageBox.Show(this, "No device selected", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
				return;
			}

			AdbConnector.getInstance().stop();

			String dev = comboBoxDevs.SelectedItem.ToString();
            int index = dev.IndexOf("]");
            dev = dev.Substring(index + 1).Trim();
			
			LogTabPage.clearLogAllTabs();
			LogTabPage.clearCache();
			
			GC.Collect();
			GC.WaitForPendingFinalizers();

			AdbConnector.getInstance().start(this, dev);
		}

		private void buttonStop_Click(object sender, EventArgs e)
		{
			
			AdbConnector.getInstance().stop();
			//LogTabPage.clearLogAllTabs();

			GC.Collect();
			GC.WaitForPendingFinalizers();

		}

		private void buttonLogcatC_Click(object sender, EventArgs e)
		{
			if (comboBoxDevs.Items.Count <= 0)
			{
				return;
			}

			AdbConnector.getInstance().stop();

			String dev = comboBoxDevs.SelectedItem.ToString();
			int index = dev.IndexOf("]");
			dev = dev.Substring(index + 1).Trim();

			AdbConnector.clearLogcat(dev);

			LogTabPage.clearLogAllTabs();
			LogTabPage.clearCache();

			GC.Collect();
			GC.WaitForPendingFinalizers();

			AdbConnector.getInstance().start(this, dev);
		}
	}
}
