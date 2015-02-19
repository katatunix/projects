namespace ReadComic
{
	partial class MainForm
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
			if (disposing && (components != null))
			{
				components.Dispose();
			}
			base.Dispose(disposing);
		}

		#region Windows Form Designer generated code

		/// <summary>
		/// Required method for Designer support - do not modify
		/// the contents of this method with the code editor.
		/// </summary>
		private void InitializeComponent()
		{
			System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(MainForm));
			this.pictureBoxComic = new System.Windows.Forms.PictureBox();
			this.panelTop = new System.Windows.Forms.Panel();
			this.panel2 = new System.Windows.Forms.Panel();
			this.label1 = new System.Windows.Forms.Label();
			this.buttonSetHome = new System.Windows.Forms.Button();
			this.comboBoxComic = new System.Windows.Forms.ComboBox();
			this.comboBoxChapter = new System.Windows.Forms.ComboBox();
			this.label2 = new System.Windows.Forms.Label();
			this.panelBottom = new System.Windows.Forms.Panel();
			this.panel1 = new System.Windows.Forms.Panel();
			this.buttonNext = new System.Windows.Forms.Button();
			this.comboBoxPage = new System.Windows.Forms.ComboBox();
			this.buttonPrev = new System.Windows.Forms.Button();
			this.panelCenter = new System.Windows.Forms.Panel();
			((System.ComponentModel.ISupportInitialize)(this.pictureBoxComic)).BeginInit();
			this.panelTop.SuspendLayout();
			this.panel2.SuspendLayout();
			this.panelBottom.SuspendLayout();
			this.panel1.SuspendLayout();
			this.panelCenter.SuspendLayout();
			this.SuspendLayout();
			// 
			// pictureBoxComic
			// 
			this.pictureBoxComic.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D;
			this.pictureBoxComic.Location = new System.Drawing.Point(234, 98);
			this.pictureBoxComic.Name = "pictureBoxComic";
			this.pictureBoxComic.Size = new System.Drawing.Size(412, 366);
			this.pictureBoxComic.SizeMode = System.Windows.Forms.PictureBoxSizeMode.Zoom;
			this.pictureBoxComic.TabIndex = 0;
			this.pictureBoxComic.TabStop = false;
			// 
			// panelTop
			// 
			this.panelTop.Controls.Add(this.panel2);
			this.panelTop.Dock = System.Windows.Forms.DockStyle.Top;
			this.panelTop.Location = new System.Drawing.Point(0, 0);
			this.panelTop.Name = "panelTop";
			this.panelTop.Size = new System.Drawing.Size(884, 50);
			this.panelTop.TabIndex = 1;
			// 
			// panel2
			// 
			this.panel2.Anchor = System.Windows.Forms.AnchorStyles.None;
			this.panel2.Controls.Add(this.label1);
			this.panel2.Controls.Add(this.buttonSetHome);
			this.panel2.Controls.Add(this.comboBoxComic);
			this.panel2.Controls.Add(this.comboBoxChapter);
			this.panel2.Controls.Add(this.label2);
			this.panel2.Location = new System.Drawing.Point(24, 0);
			this.panel2.Name = "panel2";
			this.panel2.Size = new System.Drawing.Size(831, 50);
			this.panel2.TabIndex = 5;
			// 
			// label1
			// 
			this.label1.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Left | System.Windows.Forms.AnchorStyles.Right)));
			this.label1.AutoSize = true;
			this.label1.Font = new System.Drawing.Font("Tahoma", 10F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label1.Location = new System.Drawing.Point(22, 18);
			this.label1.Name = "label1";
			this.label1.Size = new System.Drawing.Size(50, 17);
			this.label1.TabIndex = 0;
			this.label1.Text = "Comic";
			// 
			// buttonSetHome
			// 
			this.buttonSetHome.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Left | System.Windows.Forms.AnchorStyles.Right)));
			this.buttonSetHome.Location = new System.Drawing.Point(702, 15);
			this.buttonSetHome.Name = "buttonSetHome";
			this.buttonSetHome.Size = new System.Drawing.Size(101, 23);
			this.buttonSetHome.TabIndex = 4;
			this.buttonSetHome.Text = "Set Home Folder";
			this.buttonSetHome.UseVisualStyleBackColor = true;
			this.buttonSetHome.Click += new System.EventHandler(this.buttonSetHome_Click);
			// 
			// comboBoxComic
			// 
			this.comboBoxComic.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Left | System.Windows.Forms.AnchorStyles.Right)));
			this.comboBoxComic.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
			this.comboBoxComic.Font = new System.Drawing.Font("Tahoma", 9F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.comboBoxComic.FormattingEnabled = true;
			this.comboBoxComic.ItemHeight = 14;
			this.comboBoxComic.Location = new System.Drawing.Point(74, 16);
			this.comboBoxComic.MaxDropDownItems = 30;
			this.comboBoxComic.Name = "comboBoxComic";
			this.comboBoxComic.Size = new System.Drawing.Size(260, 22);
			this.comboBoxComic.TabIndex = 100;
			this.comboBoxComic.SelectedIndexChanged += new System.EventHandler(this.comboBoxComic_SelectedIndexChanged);
			// 
			// comboBoxChapter
			// 
			this.comboBoxChapter.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Left | System.Windows.Forms.AnchorStyles.Right)));
			this.comboBoxChapter.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
			this.comboBoxChapter.Font = new System.Drawing.Font("Tahoma", 9F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.comboBoxChapter.FormattingEnabled = true;
			this.comboBoxChapter.Location = new System.Drawing.Point(419, 15);
			this.comboBoxChapter.MaxDropDownItems = 20;
			this.comboBoxChapter.Name = "comboBoxChapter";
			this.comboBoxChapter.Size = new System.Drawing.Size(260, 22);
			this.comboBoxChapter.TabIndex = 200;
			this.comboBoxChapter.SelectedIndexChanged += new System.EventHandler(this.comboBoxChapter_SelectedIndexChanged);
			// 
			// label2
			// 
			this.label2.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Left | System.Windows.Forms.AnchorStyles.Right)));
			this.label2.AutoSize = true;
			this.label2.Font = new System.Drawing.Font("Tahoma", 10F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label2.Location = new System.Drawing.Point(353, 17);
			this.label2.Name = "label2";
			this.label2.Size = new System.Drawing.Size(64, 17);
			this.label2.TabIndex = 2;
			this.label2.Text = "Chapter";
			// 
			// panelBottom
			// 
			this.panelBottom.Controls.Add(this.panel1);
			this.panelBottom.Dock = System.Windows.Forms.DockStyle.Bottom;
			this.panelBottom.Location = new System.Drawing.Point(0, 616);
			this.panelBottom.Name = "panelBottom";
			this.panelBottom.Size = new System.Drawing.Size(884, 50);
			this.panelBottom.TabIndex = 2;
			// 
			// panel1
			// 
			this.panel1.Anchor = System.Windows.Forms.AnchorStyles.None;
			this.panel1.Controls.Add(this.buttonNext);
			this.panel1.Controls.Add(this.comboBoxPage);
			this.panel1.Controls.Add(this.buttonPrev);
			this.panel1.Location = new System.Drawing.Point(288, 0);
			this.panel1.Name = "panel1";
			this.panel1.Size = new System.Drawing.Size(306, 50);
			this.panel1.TabIndex = 3;
			// 
			// buttonNext
			// 
			this.buttonNext.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Left | System.Windows.Forms.AnchorStyles.Right)));
			this.buttonNext.Location = new System.Drawing.Point(219, 14);
			this.buttonNext.Name = "buttonNext";
			this.buttonNext.Size = new System.Drawing.Size(75, 23);
			this.buttonNext.TabIndex = 7;
			this.buttonNext.Text = ">>";
			this.buttonNext.UseVisualStyleBackColor = true;
			this.buttonNext.Click += new System.EventHandler(this.buttonNext_Click);
			// 
			// comboBoxPage
			// 
			this.comboBoxPage.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Left | System.Windows.Forms.AnchorStyles.Right)));
			this.comboBoxPage.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
			this.comboBoxPage.FormattingEnabled = true;
			this.comboBoxPage.Location = new System.Drawing.Point(95, 15);
			this.comboBoxPage.Name = "comboBoxPage";
			this.comboBoxPage.Size = new System.Drawing.Size(118, 21);
			this.comboBoxPage.TabIndex = 6;
			this.comboBoxPage.SelectedIndexChanged += new System.EventHandler(this.comboBoxPage_SelectedIndexChanged);
			// 
			// buttonPrev
			// 
			this.buttonPrev.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Left | System.Windows.Forms.AnchorStyles.Right)));
			this.buttonPrev.Location = new System.Drawing.Point(14, 14);
			this.buttonPrev.Name = "buttonPrev";
			this.buttonPrev.Size = new System.Drawing.Size(75, 23);
			this.buttonPrev.TabIndex = 5;
			this.buttonPrev.Text = "<<";
			this.buttonPrev.UseVisualStyleBackColor = true;
			this.buttonPrev.Click += new System.EventHandler(this.buttonPrev_Click);
			// 
			// panelCenter
			// 
			this.panelCenter.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D;
			this.panelCenter.Controls.Add(this.pictureBoxComic);
			this.panelCenter.Dock = System.Windows.Forms.DockStyle.Fill;
			this.panelCenter.Location = new System.Drawing.Point(0, 50);
			this.panelCenter.Name = "panelCenter";
			this.panelCenter.Size = new System.Drawing.Size(884, 566);
			this.panelCenter.TabIndex = 3;
			this.panelCenter.Resize += new System.EventHandler(this.panelCenter_Resize);
			// 
			// MainForm
			// 
			this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
			this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
			this.AutoScroll = true;
			this.ClientSize = new System.Drawing.Size(884, 666);
			this.Controls.Add(this.panelCenter);
			this.Controls.Add(this.panelBottom);
			this.Controls.Add(this.panelTop);
			this.Font = new System.Drawing.Font("Tahoma", 8.25F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
			this.MinimumSize = new System.Drawing.Size(595, 700);
			this.Name = "MainForm";
			this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
			this.Text = "ReadComic - Katatunix";
			this.Load += new System.EventHandler(this.MainForm_Load);
			this.FormClosing += new System.Windows.Forms.FormClosingEventHandler(this.MainForm_FormClosing);
			((System.ComponentModel.ISupportInitialize)(this.pictureBoxComic)).EndInit();
			this.panelTop.ResumeLayout(false);
			this.panel2.ResumeLayout(false);
			this.panel2.PerformLayout();
			this.panelBottom.ResumeLayout(false);
			this.panel1.ResumeLayout(false);
			this.panelCenter.ResumeLayout(false);
			this.ResumeLayout(false);

		}

		#endregion

		private System.Windows.Forms.PictureBox pictureBoxComic;
		private System.Windows.Forms.Panel panelTop;
		private System.Windows.Forms.Panel panelBottom;
		private System.Windows.Forms.Panel panelCenter;
		private System.Windows.Forms.ComboBox comboBoxPage;
		private System.Windows.Forms.Button buttonNext;
		private System.Windows.Forms.Button buttonPrev;
		private System.Windows.Forms.Panel panel1;
		private System.Windows.Forms.ComboBox comboBoxChapter;
		private System.Windows.Forms.Label label2;
		private System.Windows.Forms.Label label1;
		private System.Windows.Forms.ComboBox comboBoxComic;
		private System.Windows.Forms.Button buttonSetHome;
		private System.Windows.Forms.Panel panel2;

	}
}

