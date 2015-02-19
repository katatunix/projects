using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using FastColoredTextBoxNS;
using System.Drawing;
using System.Threading;

namespace PLog
{
	class LogTabPage : TabPage
	{
		private static TextStyle INFO_STYLE		= new TextStyle(Brushes.Black, null, FontStyle.Regular);
		private static TextStyle DEBUG_STYLE	= new TextStyle(Brushes.Blue, null, FontStyle.Regular);
		private static TextStyle WARNING_STYLE	= new TextStyle(Brushes.Olive, null, FontStyle.Regular);
		private static TextStyle ERROR_STYLE	= new TextStyle(Brushes.Red, null, FontStyle.Regular);

		private const int MAX_TAB_PAGES = 32;
		private static String MAIN_FILTER_NAME = "Main";

		private static LogTabPage[] m_sLogTabPagesList;
		private static LogTabPage m_sActiveTab;
		private static int m_sCounter = 0;
		private static String m_sCache = "";

		public static void init()
		{
			m_sLogTabPagesList = new LogTabPage[MAX_TAB_PAGES];
			for (int i = 0; i < MAX_TAB_PAGES; i++)
			{
				m_sLogTabPagesList[i] = new LogTabPage();
			}

			m_sLogTabPagesList[0].setIsInUse(true);
			m_sLogTabPagesList[0].setFilter(new CFilter(MAIN_FILTER_NAME, MAIN_FILTER_NAME, -1));
			m_sLogTabPagesList[0].refreshText();
			setActiveTab(m_sLogTabPagesList[0]);
		}

		public static void setActiveTab(LogTabPage t)
		{
			m_sActiveTab = t;
		}

		public static LogTabPage[] getLogTabPageList()
		{
			return m_sLogTabPagesList;
		}
		
		public static LogTabPage addTabPage(CFilter filter)
		{
			String newName = filter.getName();
			String newTag = filter.getTag();
			int newPid = filter.getPid();

			if (newName.Equals(MAIN_FILTER_NAME)) return null;

			for (int i = 0; i < MAX_TAB_PAGES; i++)
			{
				if (m_sLogTabPagesList[i].getIsInUse() &&
					m_sLogTabPagesList[i].getFilter().getName().Equals(newName))
				{
					return null;
				}
			}

			for (int i = 0; i < MAX_TAB_PAGES; i++)
			{
				LogTabPage t = m_sLogTabPagesList[i];
				if (!t.getIsInUse())
				{
					t.setFilter(filter);
					t.setIsInUse(true);
					t.clearLog();
					t.refreshText();

					List<LogItem> itemsList = m_sLogTabPagesList[0].getLogItemsList();
					int len = itemsList.Count;
					for (int j = 0; j < len; j++)
					{
						LogItem item = itemsList[j];
						String tag = item.getTag();
						int pid = item.getPid();
						if ((newTag == "" || newTag.Equals(tag)) && (newPid == -1 || newPid == pid))
						{
							t.appendLogItem(item);
						}
					}
					
					return t;
				}
			}

			return null;
		}

		public static bool removeTabPage(LogTabPage logTabPage)
		{
			CFilter f = logTabPage.getFilter();

			if (f != null && f.getName().Equals(MAIN_FILTER_NAME))
			{
				return false;
			}

			logTabPage.setIsInUse(false);
			logTabPage.clearLog();
			return true;
		}

		public static void clearCache()
		{
			m_sCache = "";
		}

		public static void clearLogAllTabs()
		{
			foreach (LogTabPage ltb in LogTabPage.getLogTabPageList())
			{
				if (ltb.getIsInUse())
				{
					ltb.clearLog();
				}
			}
		}

		public static void onReceive(String log)
		{
			bool isCache = true;
			if (log.EndsWith("\r\n"))
			{
				isCache = false;
			}
			log = m_sCache + log;
			String[] keys = { "\r\r\n", "\r\n" };
			String[] lines = log.Split(keys, StringSplitOptions.RemoveEmptyEntries);
			int len = lines.Length;
			if (isCache) len--;
			for (int i = 0; i < len; i++)
			{
				onReceiveLogLine(lines[i]);
			}
			m_sCache = isCache ? lines[lines.Length - 1] : "";
		}

		private static void onReceiveLogLine(String logLine)
		{
			LogItem item = new LogItem(logLine);
			
			m_sLogTabPagesList[0].appendLogItem(item); // Main filter

			String tagRecv = item.getTag();
			int pidRecv = item.getPid();

			if (item.getTag().Length > 0)
			{
				for (int i = 1; i < MAX_TAB_PAGES; i++ )
				{
					LogTabPage ltb = m_sLogTabPagesList[i];
					if (ltb.getIsInUse())
					{
						String tag = ltb.getFilter().getTag();
						int pid = ltb.getFilter().getPid();
						if ((tag == "" || tag.Equals(tagRecv)) && (pid == -1 || pid == pidRecv))
						{
							ltb.appendLogItem(item);
						}
					}
				}
			}
		}
		
		//

		public LogTabPage()
		{
			m_logItemsList = new List<LogItem>();
			m_isInUse = false;
			m_unreadCount = 0;
			m_filter = null;
			
			m_logTextBox = new FastColoredTextBox();

			m_logTextBox.AutoScrollMinSize = new System.Drawing.Size(27, 14);
			m_logTextBox.BackBrush = null;
			m_logTextBox.Cursor = System.Windows.Forms.Cursors.IBeam;
			m_logTextBox.DisabledColor = System.Drawing.Color.FromArgb(((int)(((byte)(100)))), ((int)(((byte)(180)))), ((int)(((byte)(180)))), ((int)(((byte)(180)))));
			m_logTextBox.Dock = System.Windows.Forms.DockStyle.Fill;
			m_logTextBox.Font = new System.Drawing.Font("Courier New", 9.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			m_logTextBox.Location = new System.Drawing.Point(3, 3);
			m_logTextBox.Name = "LogTextBox_" + (m_sCounter++);
			m_logTextBox.Paddings = new System.Windows.Forms.Padding(0);
			m_logTextBox.ReadOnly = false;
			m_logTextBox.SelectionColor = System.Drawing.Color.FromArgb(((int)(((byte)(50)))), ((int)(((byte)(0)))), ((int)(((byte)(0)))), ((int)(((byte)(255)))));
			m_logTextBox.Size = new System.Drawing.Size(100, 100);

			this.Controls.Add(m_logTextBox);
		}

		public void refreshText()
		{
			if (m_filter == null) return;

			String text = m_filter.getName();
			if (m_unreadCount > 0)
			{
				text += " (" + m_unreadCount + ")";
			}
			try
			{
				base.Text = text;
			}
			catch (Exception)
			{
			}
		}

		public void appendLogItem(LogItem item)
		{
			if (CConfig.isException(item.getTag(), item.getPid())) return;

			m_logItemsList.Add(item);

			//some stuffs for best performance
			m_logTextBox.BeginUpdate();
			m_logTextBox.Selection.BeginUpdate();
			//remember user selection
			var userSelection = m_logTextBox.Selection.Clone();
			//goto end of the text
			m_logTextBox.Selection.Start = m_logTextBox.LinesCount > 0 ?
				new Place(m_logTextBox[m_logTextBox.LinesCount - 1].Count, m_logTextBox.LinesCount - 1) :
				new Place(0, 0);
			//add text with predefined style
			Style style = INFO_STYLE;
			switch (item.getType())
			{
				case LogItem.DEBUG: style = DEBUG_STYLE; break;
				case LogItem.WARNING: style = WARNING_STYLE; break;
				case LogItem.ERROR: style = ERROR_STYLE; break;
			}
			m_logTextBox.InsertText(item.toString() + "\n", style);
			//restore user selection
			if (userSelection.Start != userSelection.End ||
				userSelection.Start.iLine < m_logTextBox.LinesCount - 2)
			{
				m_logTextBox.Selection.Start = userSelection.Start;
				m_logTextBox.Selection.End = userSelection.End;
			}
			else
			{
				try
				{
					m_logTextBox.DoCaretVisible();//scroll to end of the text
				}
				catch (Exception)
				{
				}
			}
			//
			m_logTextBox.Selection.EndUpdate();
			m_logTextBox.EndUpdate();

			if (m_sActiveTab != this)
			{
				m_unreadCount++;
				refreshText();
			}
			
		}

		public void clearLog()
		{
			m_logItemsList.Clear();
			m_logTextBox.Clear();
			m_unreadCount = 0;
			refreshText();
		}
		
		public FastColoredTextBox getLogTextBox() { return m_logTextBox; }
		public bool getIsInUse() { return m_isInUse; }
		public void setIsInUse(bool b) { m_isInUse = b; }
		public CFilter getFilter() { return m_filter; }
		public void setFilter(CFilter t) { m_filter = t; }
		public int getUnreadCount() { return m_unreadCount; }
		public void setUnreadCount(int c) { m_unreadCount = c; }

		public List<LogItem> getLogItemsList() { return m_logItemsList; }

		//
		private FastColoredTextBox m_logTextBox;
		private CFilter m_filter;
		private bool m_isInUse;
		private int m_unreadCount;		
		private List<LogItem> m_logItemsList;
	
	}
}
