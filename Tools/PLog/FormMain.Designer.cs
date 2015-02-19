namespace PLog
{
	partial class FormMain
	{
		/// <summary>
		/// Required designer variable.
		/// </summary>
		private System.ComponentModel.IContainer components = null;

		/// <summary>
		/// Clean up any resources being used.
		/// </summary>
		/// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
		protected override void Dispose(bool disposing)
		{
			try
			{
				if (disposing && (components != null))
				{
					components.Dispose();
				}
				base.Dispose(disposing);
			}
			catch (System.Exception)
			{
			}
		}

		#region Windows Form Designer generated code

		/// <summary>
		/// Required method for Designer support - do not modify
		/// the contents of this method with the code editor.
		/// </summary>
		private void InitializeComponent()
		{
			this.components = new System.ComponentModel.Container();
			System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(FormMain));
			this.tabControlMain = new System.Windows.Forms.TabControl();
			this.buttonAddFilter = new System.Windows.Forms.Button();
			this.buttonRemoveFilter = new System.Windows.Forms.Button();
			this.buttonClearCurrent = new System.Windows.Forms.Button();
			this.buttonGoEnd = new System.Windows.Forms.Button();
			this.buttonFind = new System.Windows.Forms.Button();
			this.contextMenuStripCopy = new System.Windows.Forms.ContextMenuStrip(this.components);
			this.copyToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
			this.labelConnectError = new System.Windows.Forms.Label();
			this.buttonConfig = new System.Windows.Forms.Button();
			this.buttonGetStackStrace = new System.Windows.Forms.Button();
			this.checkBoxWordWrap = new System.Windows.Forms.CheckBox();
			this.buttonClearAll = new System.Windows.Forms.Button();
			this.buttonRefresh = new System.Windows.Forms.Button();
			this.comboBoxDevs = new System.Windows.Forms.ComboBox();
			this.buttonConnect = new System.Windows.Forms.Button();
			this.buttonStop = new System.Windows.Forms.Button();
			this.buttonLogcatC = new System.Windows.Forms.Button();
			this.contextMenuStripCopy.SuspendLayout();
			this.SuspendLayout();
			// 
			// tabControlMain
			// 
			this.tabControlMain.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
						| System.Windows.Forms.AnchorStyles.Left)
						| System.Windows.Forms.AnchorStyles.Right)));
			this.tabControlMain.Location = new System.Drawing.Point(16, 52);
			this.tabControlMain.Name = "tabControlMain";
			this.tabControlMain.SelectedIndex = 0;
			this.tabControlMain.Size = new System.Drawing.Size(1003, 562);
			this.tabControlMain.TabIndex = 0;
			this.tabControlMain.SelectedIndexChanged += new System.EventHandler(this.tabControlMain_SelectedIndexChanged);
			// 
			// buttonAddFilter
			// 
			this.buttonAddFilter.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
			this.buttonAddFilter.Location = new System.Drawing.Point(702, 16);
			this.buttonAddFilter.Name = "buttonAddFilter";
			this.buttonAddFilter.Size = new System.Drawing.Size(105, 25);
			this.buttonAddFilter.TabIndex = 1;
			this.buttonAddFilter.Text = "Add filter...";
			this.buttonAddFilter.UseVisualStyleBackColor = true;
			this.buttonAddFilter.Click += new System.EventHandler(this.buttonAddFilter_Click);
			// 
			// buttonRemoveFilter
			// 
			this.buttonRemoveFilter.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
			this.buttonRemoveFilter.Location = new System.Drawing.Point(813, 16);
			this.buttonRemoveFilter.Name = "buttonRemoveFilter";
			this.buttonRemoveFilter.Size = new System.Drawing.Size(105, 25);
			this.buttonRemoveFilter.TabIndex = 2;
			this.buttonRemoveFilter.Text = "Remove filter";
			this.buttonRemoveFilter.UseVisualStyleBackColor = true;
			this.buttonRemoveFilter.Click += new System.EventHandler(this.buttonRemoveFilter_Click);
			// 
			// buttonClearCurrent
			// 
			this.buttonClearCurrent.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
			this.buttonClearCurrent.Location = new System.Drawing.Point(15, 624);
			this.buttonClearCurrent.Name = "buttonClearCurrent";
			this.buttonClearCurrent.Size = new System.Drawing.Size(110, 25);
			this.buttonClearCurrent.TabIndex = 3;
			this.buttonClearCurrent.Text = "&Clear current";
			this.buttonClearCurrent.UseVisualStyleBackColor = true;
			this.buttonClearCurrent.Click += new System.EventHandler(this.buttonClearCurrent_Click);
			// 
			// buttonGoEnd
			// 
			this.buttonGoEnd.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
			this.buttonGoEnd.Location = new System.Drawing.Point(405, 624);
			this.buttonGoEnd.Name = "buttonGoEnd";
			this.buttonGoEnd.Size = new System.Drawing.Size(86, 25);
			this.buttonGoEnd.TabIndex = 4;
			this.buttonGoEnd.Text = "Go end";
			this.buttonGoEnd.UseVisualStyleBackColor = true;
			this.buttonGoEnd.Click += new System.EventHandler(this.buttonGoEnd_Click);
			// 
			// buttonFind
			// 
			this.buttonFind.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
			this.buttonFind.Location = new System.Drawing.Point(498, 624);
			this.buttonFind.Name = "buttonFind";
			this.buttonFind.Size = new System.Drawing.Size(86, 25);
			this.buttonFind.TabIndex = 5;
			this.buttonFind.Text = "Find...";
			this.buttonFind.UseVisualStyleBackColor = true;
			this.buttonFind.Click += new System.EventHandler(this.buttonFind_Click);
			// 
			// contextMenuStripCopy
			// 
			this.contextMenuStripCopy.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.copyToolStripMenuItem});
			this.contextMenuStripCopy.Name = "contextMenuStripCopy";
			this.contextMenuStripCopy.Size = new System.Drawing.Size(103, 26);
			// 
			// copyToolStripMenuItem
			// 
			this.copyToolStripMenuItem.Name = "copyToolStripMenuItem";
			this.copyToolStripMenuItem.Size = new System.Drawing.Size(102, 22);
			this.copyToolStripMenuItem.Text = "Copy";
			this.copyToolStripMenuItem.Click += new System.EventHandler(this.copyToolStripMenuItem_Click);
			// 
			// labelConnectError
			// 
			this.labelConnectError.AutoSize = true;
			this.labelConnectError.ForeColor = System.Drawing.Color.Red;
			this.labelConnectError.Location = new System.Drawing.Point(533, 21);
			this.labelConnectError.Name = "labelConnectError";
			this.labelConnectError.Size = new System.Drawing.Size(0, 16);
			this.labelConnectError.TabIndex = 6;
			// 
			// buttonConfig
			// 
			this.buttonConfig.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
			this.buttonConfig.Location = new System.Drawing.Point(924, 16);
			this.buttonConfig.Name = "buttonConfig";
			this.buttonConfig.Size = new System.Drawing.Size(95, 25);
			this.buttonConfig.TabIndex = 8;
			this.buttonConfig.Text = "Config...";
			this.buttonConfig.UseVisualStyleBackColor = true;
			this.buttonConfig.Click += new System.EventHandler(this.buttonConfig_Click);
			// 
			// buttonGetStackStrace
			// 
			this.buttonGetStackStrace.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
			this.buttonGetStackStrace.Location = new System.Drawing.Point(860, 624);
			this.buttonGetStackStrace.Name = "buttonGetStackStrace";
			this.buttonGetStackStrace.Size = new System.Drawing.Size(158, 25);
			this.buttonGetStackStrace.TabIndex = 9;
			this.buttonGetStackStrace.Text = "Get stack trace";
			this.buttonGetStackStrace.UseVisualStyleBackColor = true;
			this.buttonGetStackStrace.Click += new System.EventHandler(this.buttonGetStackStrace_Click);
			// 
			// checkBoxWordWrap
			// 
			this.checkBoxWordWrap.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
			this.checkBoxWordWrap.AutoSize = true;
			this.checkBoxWordWrap.Location = new System.Drawing.Point(594, 627);
			this.checkBoxWordWrap.Name = "checkBoxWordWrap";
			this.checkBoxWordWrap.Size = new System.Drawing.Size(98, 20);
			this.checkBoxWordWrap.TabIndex = 10;
			this.checkBoxWordWrap.Text = "Word wrap";
			this.checkBoxWordWrap.UseVisualStyleBackColor = true;
			this.checkBoxWordWrap.CheckedChanged += new System.EventHandler(this.checkBoxWordWrap_CheckedChanged);
			// 
			// buttonClearAll
			// 
			this.buttonClearAll.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
			this.buttonClearAll.Location = new System.Drawing.Point(131, 624);
			this.buttonClearAll.Name = "buttonClearAll";
			this.buttonClearAll.Size = new System.Drawing.Size(110, 25);
			this.buttonClearAll.TabIndex = 11;
			this.buttonClearAll.Text = "Clear all";
			this.buttonClearAll.UseVisualStyleBackColor = true;
			this.buttonClearAll.Click += new System.EventHandler(this.buttonClearAll_Click);
			// 
			// buttonRefresh
			// 
			this.buttonRefresh.Location = new System.Drawing.Point(16, 16);
			this.buttonRefresh.Name = "buttonRefresh";
			this.buttonRefresh.Size = new System.Drawing.Size(90, 25);
			this.buttonRefresh.TabIndex = 12;
			this.buttonRefresh.Text = "Refresh devs";
			this.buttonRefresh.UseVisualStyleBackColor = true;
			this.buttonRefresh.Click += new System.EventHandler(this.buttonRefresh_Click);
			// 
			// comboBoxDevs
			// 
			this.comboBoxDevs.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left)
						| System.Windows.Forms.AnchorStyles.Right)));
			this.comboBoxDevs.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
			this.comboBoxDevs.FormattingEnabled = true;
			this.comboBoxDevs.Location = new System.Drawing.Point(112, 16);
			this.comboBoxDevs.Name = "comboBoxDevs";
			this.comboBoxDevs.Size = new System.Drawing.Size(385, 24);
			this.comboBoxDevs.TabIndex = 13;
			// 
			// buttonConnect
			// 
			this.buttonConnect.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
			this.buttonConnect.Location = new System.Drawing.Point(503, 16);
			this.buttonConnect.Name = "buttonConnect";
			this.buttonConnect.Size = new System.Drawing.Size(90, 25);
			this.buttonConnect.TabIndex = 14;
			this.buttonConnect.Text = "Connect";
			this.buttonConnect.UseVisualStyleBackColor = true;
			this.buttonConnect.Click += new System.EventHandler(this.buttonConnect_Click);
			// 
			// buttonStop
			// 
			this.buttonStop.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
			this.buttonStop.Location = new System.Drawing.Point(599, 16);
			this.buttonStop.Name = "buttonStop";
			this.buttonStop.Size = new System.Drawing.Size(90, 25);
			this.buttonStop.TabIndex = 15;
			this.buttonStop.Text = "Disconnect";
			this.buttonStop.UseVisualStyleBackColor = true;
			this.buttonStop.Click += new System.EventHandler(this.buttonStop_Click);
			// 
			// buttonLogcatC
			// 
			this.buttonLogcatC.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
			this.buttonLogcatC.Location = new System.Drawing.Point(247, 624);
			this.buttonLogcatC.Name = "buttonLogcatC";
			this.buttonLogcatC.Size = new System.Drawing.Size(110, 25);
			this.buttonLogcatC.TabIndex = 16;
			this.buttonLogcatC.Text = "logcat -c";
			this.buttonLogcatC.UseVisualStyleBackColor = true;
			this.buttonLogcatC.Click += new System.EventHandler(this.buttonLogcatC_Click);
			// 
			// FormMain
			// 
			this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
			this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
			this.ClientSize = new System.Drawing.Size(1034, 662);
			this.Controls.Add(this.buttonLogcatC);
			this.Controls.Add(this.buttonRefresh);
			this.Controls.Add(this.buttonConnect);
			this.Controls.Add(this.comboBoxDevs);
			this.Controls.Add(this.buttonStop);
			this.Controls.Add(this.checkBoxWordWrap);
			this.Controls.Add(this.buttonClearAll);
			this.Controls.Add(this.buttonConfig);
			this.Controls.Add(this.buttonGetStackStrace);
			this.Controls.Add(this.labelConnectError);
			this.Controls.Add(this.buttonFind);
			this.Controls.Add(this.buttonGoEnd);
			this.Controls.Add(this.buttonClearCurrent);
			this.Controls.Add(this.buttonRemoveFilter);
			this.Controls.Add(this.tabControlMain);
			this.Controls.Add(this.buttonAddFilter);
			this.Font = new System.Drawing.Font("Verdana", 9.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(163)));
			this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
			this.MinimumSize = new System.Drawing.Size(900, 600);
			this.Name = "FormMain";
			this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
			this.Text = "PLog 7.5 - nghia.buivan@gameloft.com";
			this.Load += new System.EventHandler(this.FormMain_Load);
			this.FormClosing += new System.Windows.Forms.FormClosingEventHandler(this.FormMain_FormClosing);
			this.contextMenuStripCopy.ResumeLayout(false);
			this.ResumeLayout(false);
			this.PerformLayout();

		}

		#endregion

		private System.Windows.Forms.TabControl tabControlMain;
		private System.Windows.Forms.Button buttonAddFilter;
		private System.Windows.Forms.Button buttonRemoveFilter;
		private System.Windows.Forms.Button buttonClearCurrent;
		private System.Windows.Forms.Button buttonGoEnd;
		private System.Windows.Forms.Button buttonFind;
		private System.Windows.Forms.ContextMenuStrip contextMenuStripCopy;
		private System.Windows.Forms.ToolStripMenuItem copyToolStripMenuItem;
		private System.Windows.Forms.Label labelConnectError;
		private System.Windows.Forms.Button buttonConfig;
		private System.Windows.Forms.Button buttonGetStackStrace;
		private System.Windows.Forms.CheckBox checkBoxWordWrap;
		private System.Windows.Forms.Button buttonClearAll;
		private System.Windows.Forms.Button buttonRefresh;
		private System.Windows.Forms.ComboBox comboBoxDevs;
		private System.Windows.Forms.Button buttonConnect;
		private System.Windows.Forms.Button buttonStop;
		private System.Windows.Forms.Button buttonLogcatC;

	}
}

