namespace kFolder
{
	partial class kFolderFrm
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
			System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(kFolderFrm));
			this.pictureBox = new System.Windows.Forms.PictureBox();
			this.buttonFinal = new System.Windows.Forms.Button();
			this.textBoxConfirm = new System.Windows.Forms.TextBox();
			this.textBoxOriginal = new System.Windows.Forms.TextBox();
			this.label1 = new System.Windows.Forms.Label();
			this.buttonNext = new System.Windows.Forms.Button();
			this.textBoxFinal = new System.Windows.Forms.TextBox();
			this.label2 = new System.Windows.Forms.Label();
			this.label3 = new System.Windows.Forms.Label();
			this.progressBar = new System.Windows.Forms.ProgressBar();
			this.tabControl = new System.Windows.Forms.TabControl();
			this.tabPagekFolder = new System.Windows.Forms.TabPage();
			this.tabPageProxy = new System.Windows.Forms.TabPage();
			this.groupBoxProxy = new System.Windows.Forms.GroupBox();
			this.textBoxDomain = new System.Windows.Forms.TextBox();
			this.textBoxPort = new System.Windows.Forms.TextBox();
			this.label4 = new System.Windows.Forms.Label();
			this.label5 = new System.Windows.Forms.Label();
			this.label6 = new System.Windows.Forms.Label();
			this.textBoxUsername = new System.Windows.Forms.TextBox();
			this.label7 = new System.Windows.Forms.Label();
			this.textBoxPassword = new System.Windows.Forms.TextBox();
			this.checkBoxUse = new System.Windows.Forms.CheckBox();
			this.buttonSave = new System.Windows.Forms.Button();
			this.tabPageAbout = new System.Windows.Forms.TabPage();
			this.pictureBox1 = new System.Windows.Forms.PictureBox();
			this.label12 = new System.Windows.Forms.Label();
			this.label11 = new System.Windows.Forms.Label();
			this.label10 = new System.Windows.Forms.Label();
			this.label9 = new System.Windows.Forms.Label();
			this.label8 = new System.Windows.Forms.Label();
			((System.ComponentModel.ISupportInitialize)(this.pictureBox)).BeginInit();
			this.tabControl.SuspendLayout();
			this.tabPagekFolder.SuspendLayout();
			this.tabPageProxy.SuspendLayout();
			this.groupBoxProxy.SuspendLayout();
			this.tabPageAbout.SuspendLayout();
			((System.ComponentModel.ISupportInitialize)(this.pictureBox1)).BeginInit();
			this.SuspendLayout();
			// 
			// pictureBox
			// 
			this.pictureBox.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D;
			this.pictureBox.Location = new System.Drawing.Point(15, 44);
			this.pictureBox.Name = "pictureBox";
			this.pictureBox.Size = new System.Drawing.Size(108, 62);
			this.pictureBox.SizeMode = System.Windows.Forms.PictureBoxSizeMode.CenterImage;
			this.pictureBox.TabIndex = 0;
			this.pictureBox.TabStop = false;
			// 
			// buttonFinal
			// 
			this.buttonFinal.Location = new System.Drawing.Point(262, 81);
			this.buttonFinal.Name = "buttonFinal";
			this.buttonFinal.Size = new System.Drawing.Size(101, 23);
			this.buttonFinal.TabIndex = 3;
			this.buttonFinal.Text = "Get direct link";
			this.buttonFinal.UseVisualStyleBackColor = true;
			this.buttonFinal.Click += new System.EventHandler(this.buttonFinal_Click);
			// 
			// textBoxConfirm
			// 
			this.textBoxConfirm.Location = new System.Drawing.Point(138, 83);
			this.textBoxConfirm.Name = "textBoxConfirm";
			this.textBoxConfirm.Size = new System.Drawing.Size(118, 20);
			this.textBoxConfirm.TabIndex = 2;
			this.textBoxConfirm.KeyPress += new System.Windows.Forms.KeyPressEventHandler(this.textBoxConfirm_KeyPress);
			// 
			// textBoxOriginal
			// 
			this.textBoxOriginal.Location = new System.Drawing.Point(95, 16);
			this.textBoxOriginal.Name = "textBoxOriginal";
			this.textBoxOriginal.Size = new System.Drawing.Size(190, 20);
			this.textBoxOriginal.TabIndex = 0;
			// 
			// label1
			// 
			this.label1.AutoSize = true;
			this.label1.Location = new System.Drawing.Point(17, 19);
			this.label1.Name = "label1";
			this.label1.Size = new System.Drawing.Size(77, 13);
			this.label1.TabIndex = 100;
			this.label1.Text = "Paste link here";
			// 
			// buttonNext
			// 
			this.buttonNext.Location = new System.Drawing.Point(288, 14);
			this.buttonNext.Name = "buttonNext";
			this.buttonNext.Size = new System.Drawing.Size(75, 23);
			this.buttonNext.TabIndex = 1;
			this.buttonNext.Text = "Next";
			this.buttonNext.UseVisualStyleBackColor = true;
			this.buttonNext.Click += new System.EventHandler(this.buttonNext_Click);
			// 
			// textBoxFinal
			// 
			this.textBoxFinal.Location = new System.Drawing.Point(15, 137);
			this.textBoxFinal.Name = "textBoxFinal";
			this.textBoxFinal.ReadOnly = true;
			this.textBoxFinal.Size = new System.Drawing.Size(348, 20);
			this.textBoxFinal.TabIndex = 4;
			this.textBoxFinal.MouseDown += new System.Windows.Forms.MouseEventHandler(this.textBoxFinal_MouseDown);
			this.textBoxFinal.Enter += new System.EventHandler(this.textBoxFinal_Enter);
			// 
			// label2
			// 
			this.label2.AutoSize = true;
			this.label2.Location = new System.Drawing.Point(135, 63);
			this.label2.Name = "label2";
			this.label2.Size = new System.Drawing.Size(67, 13);
			this.label2.TabIndex = 101;
			this.label2.Text = "Type 4 digits";
			// 
			// label3
			// 
			this.label3.AutoSize = true;
			this.label3.Location = new System.Drawing.Point(189, 121);
			this.label3.Name = "label3";
			this.label3.Size = new System.Drawing.Size(174, 13);
			this.label3.TabIndex = 103;
			this.label3.Text = "Double click on textbox to select all";
			// 
			// progressBar
			// 
			this.progressBar.Location = new System.Drawing.Point(262, 46);
			this.progressBar.Name = "progressBar";
			this.progressBar.Size = new System.Drawing.Size(100, 23);
			this.progressBar.Style = System.Windows.Forms.ProgressBarStyle.Marquee;
			this.progressBar.TabIndex = 102;
			this.progressBar.Visible = false;
			// 
			// tabControl
			// 
			this.tabControl.Controls.Add(this.tabPagekFolder);
			this.tabControl.Controls.Add(this.tabPageProxy);
			this.tabControl.Controls.Add(this.tabPageAbout);
			this.tabControl.Location = new System.Drawing.Point(5, 5);
			this.tabControl.Name = "tabControl";
			this.tabControl.SelectedIndex = 0;
			this.tabControl.Size = new System.Drawing.Size(384, 196);
			this.tabControl.TabIndex = 11;
			// 
			// tabPagekFolder
			// 
			this.tabPagekFolder.Controls.Add(this.label1);
			this.tabPagekFolder.Controls.Add(this.progressBar);
			this.tabPagekFolder.Controls.Add(this.pictureBox);
			this.tabPagekFolder.Controls.Add(this.label3);
			this.tabPagekFolder.Controls.Add(this.buttonFinal);
			this.tabPagekFolder.Controls.Add(this.label2);
			this.tabPagekFolder.Controls.Add(this.textBoxConfirm);
			this.tabPagekFolder.Controls.Add(this.textBoxFinal);
			this.tabPagekFolder.Controls.Add(this.textBoxOriginal);
			this.tabPagekFolder.Controls.Add(this.buttonNext);
			this.tabPagekFolder.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.tabPagekFolder.Location = new System.Drawing.Point(4, 22);
			this.tabPagekFolder.Name = "tabPagekFolder";
			this.tabPagekFolder.Padding = new System.Windows.Forms.Padding(3);
			this.tabPagekFolder.Size = new System.Drawing.Size(376, 170);
			this.tabPagekFolder.TabIndex = 0;
			this.tabPagekFolder.Text = "kFolder";
			this.tabPagekFolder.UseVisualStyleBackColor = true;
			this.tabPagekFolder.Enter += new System.EventHandler(this.tabPagekFolder_Enter);
			// 
			// tabPageProxy
			// 
			this.tabPageProxy.Controls.Add(this.groupBoxProxy);
			this.tabPageProxy.Controls.Add(this.checkBoxUse);
			this.tabPageProxy.Controls.Add(this.buttonSave);
			this.tabPageProxy.Location = new System.Drawing.Point(4, 22);
			this.tabPageProxy.Name = "tabPageProxy";
			this.tabPageProxy.Padding = new System.Windows.Forms.Padding(3);
			this.tabPageProxy.Size = new System.Drawing.Size(376, 170);
			this.tabPageProxy.TabIndex = 1;
			this.tabPageProxy.Text = "Proxy";
			this.tabPageProxy.UseVisualStyleBackColor = true;
			// 
			// groupBoxProxy
			// 
			this.groupBoxProxy.Controls.Add(this.textBoxDomain);
			this.groupBoxProxy.Controls.Add(this.textBoxPort);
			this.groupBoxProxy.Controls.Add(this.label4);
			this.groupBoxProxy.Controls.Add(this.label5);
			this.groupBoxProxy.Controls.Add(this.label6);
			this.groupBoxProxy.Controls.Add(this.textBoxUsername);
			this.groupBoxProxy.Controls.Add(this.label7);
			this.groupBoxProxy.Controls.Add(this.textBoxPassword);
			this.groupBoxProxy.Location = new System.Drawing.Point(14, 19);
			this.groupBoxProxy.Name = "groupBoxProxy";
			this.groupBoxProxy.Size = new System.Drawing.Size(247, 129);
			this.groupBoxProxy.TabIndex = 15;
			this.groupBoxProxy.TabStop = false;
			this.groupBoxProxy.Text = "Proxy";
			// 
			// textBoxDomain
			// 
			this.textBoxDomain.Location = new System.Drawing.Point(67, 19);
			this.textBoxDomain.Name = "textBoxDomain";
			this.textBoxDomain.Size = new System.Drawing.Size(170, 20);
			this.textBoxDomain.TabIndex = 0;
			// 
			// textBoxPort
			// 
			this.textBoxPort.Location = new System.Drawing.Point(67, 45);
			this.textBoxPort.Name = "textBoxPort";
			this.textBoxPort.Size = new System.Drawing.Size(170, 20);
			this.textBoxPort.TabIndex = 1;
			// 
			// label4
			// 
			this.label4.AutoSize = true;
			this.label4.Location = new System.Drawing.Point(17, 22);
			this.label4.Name = "label4";
			this.label4.Size = new System.Drawing.Size(43, 13);
			this.label4.TabIndex = 2;
			this.label4.Text = "Domain";
			// 
			// label5
			// 
			this.label5.AutoSize = true;
			this.label5.Location = new System.Drawing.Point(34, 48);
			this.label5.Name = "label5";
			this.label5.Size = new System.Drawing.Size(26, 13);
			this.label5.TabIndex = 3;
			this.label5.Text = "Port";
			// 
			// label6
			// 
			this.label6.AutoSize = true;
			this.label6.Location = new System.Drawing.Point(7, 100);
			this.label6.Name = "label6";
			this.label6.Size = new System.Drawing.Size(53, 13);
			this.label6.TabIndex = 7;
			this.label6.Text = "Password";
			// 
			// textBoxUsername
			// 
			this.textBoxUsername.Location = new System.Drawing.Point(67, 71);
			this.textBoxUsername.Name = "textBoxUsername";
			this.textBoxUsername.Size = new System.Drawing.Size(170, 20);
			this.textBoxUsername.TabIndex = 4;
			// 
			// label7
			// 
			this.label7.AutoSize = true;
			this.label7.Location = new System.Drawing.Point(5, 74);
			this.label7.Name = "label7";
			this.label7.Size = new System.Drawing.Size(55, 13);
			this.label7.TabIndex = 6;
			this.label7.Text = "Username";
			// 
			// textBoxPassword
			// 
			this.textBoxPassword.Location = new System.Drawing.Point(67, 97);
			this.textBoxPassword.Name = "textBoxPassword";
			this.textBoxPassword.Size = new System.Drawing.Size(170, 20);
			this.textBoxPassword.TabIndex = 5;
			this.textBoxPassword.UseSystemPasswordChar = true;
			// 
			// checkBoxUse
			// 
			this.checkBoxUse.AutoSize = true;
			this.checkBoxUse.Checked = true;
			this.checkBoxUse.CheckState = System.Windows.Forms.CheckState.Checked;
			this.checkBoxUse.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.checkBoxUse.Location = new System.Drawing.Point(278, 86);
			this.checkBoxUse.Name = "checkBoxUse";
			this.checkBoxUse.Size = new System.Drawing.Size(83, 17);
			this.checkBoxUse.TabIndex = 14;
			this.checkBoxUse.Text = "Use Proxy";
			this.checkBoxUse.UseVisualStyleBackColor = true;
			this.checkBoxUse.CheckedChanged += new System.EventHandler(this.checkBoxUse_CheckedChanged);
			// 
			// buttonSave
			// 
			this.buttonSave.Location = new System.Drawing.Point(278, 122);
			this.buttonSave.Name = "buttonSave";
			this.buttonSave.Size = new System.Drawing.Size(75, 23);
			this.buttonSave.TabIndex = 12;
			this.buttonSave.Text = "Save";
			this.buttonSave.UseVisualStyleBackColor = true;
			this.buttonSave.Click += new System.EventHandler(this.buttonSave_Click);
			// 
			// tabPageAbout
			// 
			this.tabPageAbout.Controls.Add(this.pictureBox1);
			this.tabPageAbout.Controls.Add(this.label12);
			this.tabPageAbout.Controls.Add(this.label11);
			this.tabPageAbout.Controls.Add(this.label10);
			this.tabPageAbout.Controls.Add(this.label9);
			this.tabPageAbout.Controls.Add(this.label8);
			this.tabPageAbout.Location = new System.Drawing.Point(4, 22);
			this.tabPageAbout.Name = "tabPageAbout";
			this.tabPageAbout.Padding = new System.Windows.Forms.Padding(3);
			this.tabPageAbout.Size = new System.Drawing.Size(376, 170);
			this.tabPageAbout.TabIndex = 2;
			this.tabPageAbout.Text = "About";
			this.tabPageAbout.UseVisualStyleBackColor = true;
			// 
			// pictureBox1
			// 
			this.pictureBox1.Image = global::kFolder.Properties.Resources.katatunix144;
			this.pictureBox1.Location = new System.Drawing.Point(225, 12);
			this.pictureBox1.Name = "pictureBox1";
			this.pictureBox1.Size = new System.Drawing.Size(144, 144);
			this.pictureBox1.TabIndex = 5;
			this.pictureBox1.TabStop = false;
			// 
			// label12
			// 
			this.label12.AutoSize = true;
			this.label12.Font = new System.Drawing.Font("Tahoma", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label12.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(192)))), ((int)(((byte)(0)))), ((int)(((byte)(0)))));
			this.label12.Location = new System.Drawing.Point(21, 124);
			this.label12.Name = "label12";
			this.label12.Size = new System.Drawing.Size(181, 13);
			this.label12.TabIndex = 4;
			this.label12.Text = "Blog: katatunix.wordpress.com";
			// 
			// label11
			// 
			this.label11.AutoSize = true;
			this.label11.Font = new System.Drawing.Font("Tahoma", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label11.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(192)))), ((int)(((byte)(0)))), ((int)(((byte)(0)))));
			this.label11.Location = new System.Drawing.Point(21, 32);
			this.label11.Name = "label11";
			this.label11.Size = new System.Drawing.Size(133, 13);
			this.label11.TabIndex = 3;
			this.label11.Text = "kFolder 2.3 - Freeware";
			// 
			// label10
			// 
			this.label10.AutoSize = true;
			this.label10.Font = new System.Drawing.Font("Tahoma", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label10.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(192)))), ((int)(((byte)(0)))), ((int)(((byte)(0)))));
			this.label10.Location = new System.Drawing.Point(21, 101);
			this.label10.Name = "label10";
			this.label10.Size = new System.Drawing.Size(149, 13);
			this.label10.TabIndex = 2;
			this.label10.Text = "MSN: katatunix@live.com";
			// 
			// label9
			// 
			this.label9.AutoSize = true;
			this.label9.Font = new System.Drawing.Font("Tahoma", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label9.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(192)))), ((int)(((byte)(0)))), ((int)(((byte)(0)))));
			this.label9.Location = new System.Drawing.Point(21, 78);
			this.label9.Name = "label9";
			this.label9.Size = new System.Drawing.Size(166, 13);
			this.label9.TabIndex = 1;
			this.label9.Text = "Email: katatunix@gmail.com";
			// 
			// label8
			// 
			this.label8.AutoSize = true;
			this.label8.Font = new System.Drawing.Font("Tahoma", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label8.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(192)))), ((int)(((byte)(0)))), ((int)(((byte)(0)))));
			this.label8.Location = new System.Drawing.Point(21, 55);
			this.label8.Name = "label8";
			this.label8.Size = new System.Drawing.Size(192, 13);
			this.label8.TabIndex = 0;
			this.label8.Text = "copyleft © 2012 by Bùi Văn Nghĩa";
			// 
			// kFolderFrm
			// 
			this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
			this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
			this.ClientSize = new System.Drawing.Size(390, 202);
			this.Controls.Add(this.tabControl);
			this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.Fixed3D;
			this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
			this.MaximizeBox = false;
			this.Name = "kFolderFrm";
			this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
			this.Text = "kFolder 2.3";
			this.Load += new System.EventHandler(this.kFolderFrm_Load);
			this.FormClosed += new System.Windows.Forms.FormClosedEventHandler(this.kFolderFrm_FormClosed);
			((System.ComponentModel.ISupportInitialize)(this.pictureBox)).EndInit();
			this.tabControl.ResumeLayout(false);
			this.tabPagekFolder.ResumeLayout(false);
			this.tabPagekFolder.PerformLayout();
			this.tabPageProxy.ResumeLayout(false);
			this.tabPageProxy.PerformLayout();
			this.groupBoxProxy.ResumeLayout(false);
			this.groupBoxProxy.PerformLayout();
			this.tabPageAbout.ResumeLayout(false);
			this.tabPageAbout.PerformLayout();
			((System.ComponentModel.ISupportInitialize)(this.pictureBox1)).EndInit();
			this.ResumeLayout(false);

		}

		#endregion

		private System.Windows.Forms.PictureBox pictureBox;
		private System.Windows.Forms.Button buttonFinal;
		private System.Windows.Forms.TextBox textBoxConfirm;
		private System.Windows.Forms.TextBox textBoxOriginal;
		private System.Windows.Forms.Label label1;
		private System.Windows.Forms.Button buttonNext;
		private System.Windows.Forms.TextBox textBoxFinal;
		private System.Windows.Forms.Label label2;
		private System.Windows.Forms.Label label3;
		private System.Windows.Forms.ProgressBar progressBar;
		private System.Windows.Forms.TabControl tabControl;
		private System.Windows.Forms.TabPage tabPagekFolder;
		private System.Windows.Forms.TabPage tabPageProxy;
		private System.Windows.Forms.GroupBox groupBoxProxy;
		private System.Windows.Forms.TextBox textBoxDomain;
		private System.Windows.Forms.TextBox textBoxPort;
		private System.Windows.Forms.Label label4;
		private System.Windows.Forms.Label label5;
		private System.Windows.Forms.Label label6;
		private System.Windows.Forms.TextBox textBoxUsername;
		private System.Windows.Forms.Label label7;
		private System.Windows.Forms.TextBox textBoxPassword;
		private System.Windows.Forms.CheckBox checkBoxUse;
		private System.Windows.Forms.Button buttonSave;
		private System.Windows.Forms.TabPage tabPageAbout;
		private System.Windows.Forms.Label label12;
		private System.Windows.Forms.Label label11;
		private System.Windows.Forms.Label label10;
		private System.Windows.Forms.Label label9;
		private System.Windows.Forms.Label label8;
		private System.Windows.Forms.PictureBox pictureBox1;

	}
}

