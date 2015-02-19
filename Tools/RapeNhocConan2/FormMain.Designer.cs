namespace RapeNhocConan2
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
			this.listBoxManga = new System.Windows.Forms.ListBox();
			this.buttonManga = new System.Windows.Forms.Button();
			this.listBoxChapter = new System.Windows.Forms.ListBox();
			this.buttonChapter = new System.Windows.Forms.Button();
			this.buttonSelectAll = new System.Windows.Forms.Button();
			this.buttonUnselect = new System.Windows.Forms.Button();
			this.buttonProcess = new System.Windows.Forms.Button();
			this.buttonProxy = new System.Windows.Forms.Button();
			this.label1 = new System.Windows.Forms.Label();
			this.label2 = new System.Windows.Forms.Label();
			this.label3 = new System.Windows.Forms.Label();
			this.radioButtonSavePic = new System.Windows.Forms.RadioButton();
			this.radioButtonSaveTxt = new System.Windows.Forms.RadioButton();
			this.buttonSelectLocation = new System.Windows.Forms.Button();
			this.folderBrowserDialogSelectLocation = new System.Windows.Forms.FolderBrowserDialog();
			this.SuspendLayout();
			// 
			// listBoxManga
			// 
			this.listBoxManga.FormattingEnabled = true;
			this.listBoxManga.ItemHeight = 14;
			this.listBoxManga.Location = new System.Drawing.Point(14, 41);
			this.listBoxManga.Name = "listBoxManga";
			this.listBoxManga.Size = new System.Drawing.Size(291, 438);
			this.listBoxManga.TabIndex = 0;
			this.listBoxManga.SelectedIndexChanged += new System.EventHandler(this.listBoxManga_SelectedIndexChanged);
			// 
			// buttonManga
			// 
			this.buttonManga.Location = new System.Drawing.Point(113, 486);
			this.buttonManga.Name = "buttonManga";
			this.buttonManga.Size = new System.Drawing.Size(149, 25);
			this.buttonManga.TabIndex = 1;
			this.buttonManga.Text = "Refresh Mangas List";
			this.buttonManga.UseVisualStyleBackColor = true;
			this.buttonManga.Click += new System.EventHandler(this.buttonManga_Click);
			// 
			// listBoxChapter
			// 
			this.listBoxChapter.FormattingEnabled = true;
			this.listBoxChapter.ItemHeight = 14;
			this.listBoxChapter.Location = new System.Drawing.Point(407, 83);
			this.listBoxChapter.Name = "listBoxChapter";
			this.listBoxChapter.SelectionMode = System.Windows.Forms.SelectionMode.MultiExtended;
			this.listBoxChapter.Size = new System.Drawing.Size(291, 396);
			this.listBoxChapter.TabIndex = 2;
			// 
			// buttonChapter
			// 
			this.buttonChapter.Location = new System.Drawing.Point(314, 220);
			this.buttonChapter.Name = "buttonChapter";
			this.buttonChapter.Size = new System.Drawing.Size(84, 67);
			this.buttonChapter.TabIndex = 3;
			this.buttonChapter.Text = "Refresh Chapters List";
			this.buttonChapter.UseVisualStyleBackColor = true;
			this.buttonChapter.Click += new System.EventHandler(this.buttonChapter_Click);
			// 
			// buttonSelectAll
			// 
			this.buttonSelectAll.Location = new System.Drawing.Point(414, 486);
			this.buttonSelectAll.Name = "buttonSelectAll";
			this.buttonSelectAll.Size = new System.Drawing.Size(87, 25);
			this.buttonSelectAll.TabIndex = 4;
			this.buttonSelectAll.Text = "Select All";
			this.buttonSelectAll.UseVisualStyleBackColor = true;
			this.buttonSelectAll.Click += new System.EventHandler(this.buttonSelectAll_Click);
			// 
			// buttonUnselect
			// 
			this.buttonUnselect.Location = new System.Drawing.Point(509, 486);
			this.buttonUnselect.Name = "buttonUnselect";
			this.buttonUnselect.Size = new System.Drawing.Size(87, 25);
			this.buttonUnselect.TabIndex = 5;
			this.buttonUnselect.Text = "Unselect All";
			this.buttonUnselect.UseVisualStyleBackColor = true;
			this.buttonUnselect.Click += new System.EventHandler(this.buttonUnselect_Click);
			// 
			// buttonProcess
			// 
			this.buttonProcess.Location = new System.Drawing.Point(603, 486);
			this.buttonProcess.Name = "buttonProcess";
			this.buttonProcess.Size = new System.Drawing.Size(83, 25);
			this.buttonProcess.TabIndex = 6;
			this.buttonProcess.Text = "Download...";
			this.buttonProcess.UseVisualStyleBackColor = true;
			this.buttonProcess.Click += new System.EventHandler(this.buttonProcess_Click);
			// 
			// buttonProxy
			// 
			this.buttonProxy.Location = new System.Drawing.Point(14, 487);
			this.buttonProxy.Name = "buttonProxy";
			this.buttonProxy.Size = new System.Drawing.Size(75, 23);
			this.buttonProxy.TabIndex = 8;
			this.buttonProxy.Text = "Proxy...";
			this.buttonProxy.UseVisualStyleBackColor = true;
			this.buttonProxy.Click += new System.EventHandler(this.buttonProxy_Click);
			// 
			// label1
			// 
			this.label1.AutoSize = true;
			this.label1.Font = new System.Drawing.Font("Tahoma", 9F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label1.Location = new System.Drawing.Point(12, 18);
			this.label1.Name = "label1";
			this.label1.Size = new System.Drawing.Size(80, 14);
			this.label1.TabIndex = 9;
			this.label1.Text = "Mangas List";
			// 
			// label2
			// 
			this.label2.AutoSize = true;
			this.label2.Font = new System.Drawing.Font("Tahoma", 9F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label2.Location = new System.Drawing.Point(404, 59);
			this.label2.Name = "label2";
			this.label2.Size = new System.Drawing.Size(88, 14);
			this.label2.TabIndex = 10;
			this.label2.Text = "Chapters List";
			// 
			// label3
			// 
			this.label3.AutoSize = true;
			this.label3.Font = new System.Drawing.Font("Tahoma", 8F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label3.Location = new System.Drawing.Point(498, 60);
			this.label3.Name = "label3";
			this.label3.Size = new System.Drawing.Size(105, 13);
			this.label3.TabIndex = 11;
			this.label3.Text = "(Select one or more)";
			// 
			// radioButtonSavePic
			// 
			this.radioButtonSavePic.AutoSize = true;
			this.radioButtonSavePic.Checked = true;
			this.radioButtonSavePic.Location = new System.Drawing.Point(414, 16);
			this.radioButtonSavePic.Name = "radioButtonSavePic";
			this.radioButtonSavePic.Size = new System.Drawing.Size(70, 18);
			this.radioButtonSavePic.TabIndex = 13;
			this.radioButtonSavePic.TabStop = true;
			this.radioButtonSavePic.Text = "Save Pic";
			this.radioButtonSavePic.UseVisualStyleBackColor = true;
			// 
			// radioButtonSaveTxt
			// 
			this.radioButtonSaveTxt.AutoSize = true;
			this.radioButtonSaveTxt.Location = new System.Drawing.Point(487, 16);
			this.radioButtonSaveTxt.Name = "radioButtonSaveTxt";
			this.radioButtonSaveTxt.Size = new System.Drawing.Size(74, 18);
			this.radioButtonSaveTxt.TabIndex = 14;
			this.radioButtonSaveTxt.Text = "Save Txt";
			this.radioButtonSaveTxt.UseVisualStyleBackColor = true;
			// 
			// buttonSelectLocation
			// 
			this.buttonSelectLocation.Font = new System.Drawing.Font("Tahoma", 8F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.buttonSelectLocation.Location = new System.Drawing.Point(567, 14);
			this.buttonSelectLocation.Name = "buttonSelectLocation";
			this.buttonSelectLocation.Size = new System.Drawing.Size(133, 23);
			this.buttonSelectLocation.TabIndex = 15;
			this.buttonSelectLocation.Text = "Download Location...";
			this.buttonSelectLocation.UseVisualStyleBackColor = true;
			this.buttonSelectLocation.Click += new System.EventHandler(this.buttonSelectLocation_Click);
			// 
			// FormMain
			// 
			this.AutoScaleDimensions = new System.Drawing.SizeF(7F, 14F);
			this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
			this.ClientSize = new System.Drawing.Size(712, 520);
			this.Controls.Add(this.buttonSelectLocation);
			this.Controls.Add(this.radioButtonSaveTxt);
			this.Controls.Add(this.radioButtonSavePic);
			this.Controls.Add(this.label3);
			this.Controls.Add(this.label2);
			this.Controls.Add(this.label1);
			this.Controls.Add(this.buttonProxy);
			this.Controls.Add(this.listBoxChapter);
			this.Controls.Add(this.buttonProcess);
			this.Controls.Add(this.buttonUnselect);
			this.Controls.Add(this.buttonSelectAll);
			this.Controls.Add(this.buttonChapter);
			this.Controls.Add(this.buttonManga);
			this.Controls.Add(this.listBoxManga);
			this.Font = new System.Drawing.Font("Tahoma", 9F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.Fixed3D;
			this.MaximizeBox = false;
			this.Name = "FormMain";
			this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
			this.Text = "RapeNhocConan v2.1 (c) katatunix@gmail.com";
			this.Load += new System.EventHandler(this.FormMain_Load);
			this.FormClosing += new System.Windows.Forms.FormClosingEventHandler(this.FormMain_FormClosing);
			this.ResumeLayout(false);
			this.PerformLayout();

		}

		#endregion

		private System.Windows.Forms.ListBox listBoxManga;
		private System.Windows.Forms.Button buttonManga;
		private System.Windows.Forms.ListBox listBoxChapter;
		private System.Windows.Forms.Button buttonChapter;
		private System.Windows.Forms.Button buttonSelectAll;
		private System.Windows.Forms.Button buttonUnselect;
		private System.Windows.Forms.Button buttonProcess;
		private System.Windows.Forms.Button buttonProxy;
		private System.Windows.Forms.Label label1;
		private System.Windows.Forms.Label label2;
		private System.Windows.Forms.Label label3;
		private System.Windows.Forms.RadioButton radioButtonSavePic;
		private System.Windows.Forms.RadioButton radioButtonSaveTxt;
		private System.Windows.Forms.Button buttonSelectLocation;
		private System.Windows.Forms.FolderBrowserDialog folderBrowserDialogSelectLocation;

	}
}

