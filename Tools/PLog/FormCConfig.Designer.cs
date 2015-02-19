namespace PLog
{
	partial class FormCConfig
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
			this.buttonCancel = new System.Windows.Forms.Button();
			this.buttonOK = new System.Windows.Forms.Button();
			this.textBoxSoLib = new System.Windows.Forms.TextBox();
			this.label3 = new System.Windows.Forms.Label();
			this.textBoxPython = new System.Windows.Forms.TextBox();
			this.label2 = new System.Windows.Forms.Label();
			this.textBoxNDK = new System.Windows.Forms.TextBox();
			this.label1 = new System.Windows.Forms.Label();
			this.label5 = new System.Windows.Forms.Label();
			this.buttonNDK = new System.Windows.Forms.Button();
			this.buttonPython = new System.Windows.Forms.Button();
			this.buttonSo = new System.Windows.Forms.Button();
			this.folderBrowserDialog1 = new System.Windows.Forms.FolderBrowserDialog();
			this.openFileDialog1 = new System.Windows.Forms.OpenFileDialog();
			this.tabControl1 = new System.Windows.Forms.TabControl();
			this.tabPage1 = new System.Windows.Forms.TabPage();
			this.tabPage2 = new System.Windows.Forms.TabPage();
			this.richTextBox2 = new System.Windows.Forms.RichTextBox();
			this.label6 = new System.Windows.Forms.Label();
			this.richTextBox1 = new System.Windows.Forms.RichTextBox();
			this.tabControl1.SuspendLayout();
			this.tabPage1.SuspendLayout();
			this.tabPage2.SuspendLayout();
			this.SuspendLayout();
			// 
			// buttonCancel
			// 
			this.buttonCancel.Location = new System.Drawing.Point(331, 275);
			this.buttonCancel.Name = "buttonCancel";
			this.buttonCancel.Size = new System.Drawing.Size(99, 29);
			this.buttonCancel.TabIndex = 15;
			this.buttonCancel.Text = "Cancel";
			this.buttonCancel.UseVisualStyleBackColor = true;
			this.buttonCancel.Click += new System.EventHandler(this.buttonCancel_Click);
			// 
			// buttonOK
			// 
			this.buttonOK.Location = new System.Drawing.Point(223, 275);
			this.buttonOK.Name = "buttonOK";
			this.buttonOK.Size = new System.Drawing.Size(99, 29);
			this.buttonOK.TabIndex = 14;
			this.buttonOK.Text = "OK";
			this.buttonOK.UseVisualStyleBackColor = true;
			this.buttonOK.Click += new System.EventHandler(this.buttonOK_Click);
			// 
			// textBoxSoLib
			// 
			this.textBoxSoLib.Location = new System.Drawing.Point(169, 147);
			this.textBoxSoLib.Name = "textBoxSoLib";
			this.textBoxSoLib.Size = new System.Drawing.Size(339, 23);
			this.textBoxSoLib.TabIndex = 13;
			// 
			// label3
			// 
			this.label3.AutoSize = true;
			this.label3.Location = new System.Drawing.Point(76, 150);
			this.label3.Name = "label3";
			this.label3.Size = new System.Drawing.Size(89, 16);
			this.label3.TabIndex = 12;
			this.label3.Text = "SO_LIBRARY";
			// 
			// textBoxPython
			// 
			this.textBoxPython.Location = new System.Drawing.Point(169, 112);
			this.textBoxPython.Name = "textBoxPython";
			this.textBoxPython.Size = new System.Drawing.Size(339, 23);
			this.textBoxPython.TabIndex = 11;
			// 
			// label2
			// 
			this.label2.AutoSize = true;
			this.label2.Location = new System.Drawing.Point(58, 115);
			this.label2.Name = "label2";
			this.label2.Size = new System.Drawing.Size(108, 16);
			this.label2.TabIndex = 10;
			this.label2.Text = "PYTHON_HOME";
			// 
			// textBoxNDK
			// 
			this.textBoxNDK.Location = new System.Drawing.Point(169, 76);
			this.textBoxNDK.Name = "textBoxNDK";
			this.textBoxNDK.Size = new System.Drawing.Size(339, 23);
			this.textBoxNDK.TabIndex = 9;
			// 
			// label1
			// 
			this.label1.AutoSize = true;
			this.label1.Location = new System.Drawing.Point(18, 79);
			this.label1.Name = "label1";
			this.label1.Size = new System.Drawing.Size(147, 16);
			this.label1.TabIndex = 8;
			this.label1.Text = "ANDROID_NDK_HOME";
			// 
			// label5
			// 
			this.label5.AutoSize = true;
			this.label5.Font = new System.Drawing.Font("Verdana", 9.75F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label5.Location = new System.Drawing.Point(34, 26);
			this.label5.Name = "label5";
			this.label5.Size = new System.Drawing.Size(219, 16);
			this.label5.TabIndex = 18;
			this.label5.Text = "Please provide absolute path";
			// 
			// buttonNDK
			// 
			this.buttonNDK.Location = new System.Drawing.Point(515, 75);
			this.buttonNDK.Name = "buttonNDK";
			this.buttonNDK.Size = new System.Drawing.Size(86, 25);
			this.buttonNDK.TabIndex = 19;
			this.buttonNDK.Text = "Browse...";
			this.buttonNDK.UseVisualStyleBackColor = true;
			this.buttonNDK.Click += new System.EventHandler(this.buttonNDK_Click);
			// 
			// buttonPython
			// 
			this.buttonPython.Location = new System.Drawing.Point(515, 111);
			this.buttonPython.Name = "buttonPython";
			this.buttonPython.Size = new System.Drawing.Size(86, 25);
			this.buttonPython.TabIndex = 20;
			this.buttonPython.Text = "Browse...";
			this.buttonPython.UseVisualStyleBackColor = true;
			this.buttonPython.Click += new System.EventHandler(this.buttonPython_Click);
			// 
			// buttonSo
			// 
			this.buttonSo.Location = new System.Drawing.Point(515, 147);
			this.buttonSo.Name = "buttonSo";
			this.buttonSo.Size = new System.Drawing.Size(86, 25);
			this.buttonSo.TabIndex = 21;
			this.buttonSo.Text = "Browse...";
			this.buttonSo.UseVisualStyleBackColor = true;
			this.buttonSo.Click += new System.EventHandler(this.buttonSo_Click);
			// 
			// openFileDialog1
			// 
			this.openFileDialog1.FileName = "openFileDialog1";
			// 
			// tabControl1
			// 
			this.tabControl1.Controls.Add(this.tabPage1);
			this.tabControl1.Controls.Add(this.tabPage2);
			this.tabControl1.Location = new System.Drawing.Point(12, 12);
			this.tabControl1.Name = "tabControl1";
			this.tabControl1.SelectedIndex = 0;
			this.tabControl1.Size = new System.Drawing.Size(629, 250);
			this.tabControl1.TabIndex = 23;
			// 
			// tabPage1
			// 
			this.tabPage1.Controls.Add(this.label5);
			this.tabPage1.Controls.Add(this.label1);
			this.tabPage1.Controls.Add(this.buttonSo);
			this.tabPage1.Controls.Add(this.textBoxNDK);
			this.tabPage1.Controls.Add(this.buttonPython);
			this.tabPage1.Controls.Add(this.label2);
			this.tabPage1.Controls.Add(this.buttonNDK);
			this.tabPage1.Controls.Add(this.textBoxPython);
			this.tabPage1.Controls.Add(this.label3);
			this.tabPage1.Controls.Add(this.textBoxSoLib);
			this.tabPage1.Location = new System.Drawing.Point(4, 25);
			this.tabPage1.Name = "tabPage1";
			this.tabPage1.Padding = new System.Windows.Forms.Padding(3);
			this.tabPage1.Size = new System.Drawing.Size(621, 221);
			this.tabPage1.TabIndex = 0;
			this.tabPage1.Text = "Stack trace";
			this.tabPage1.UseVisualStyleBackColor = true;
			// 
			// tabPage2
			// 
			this.tabPage2.Controls.Add(this.richTextBox2);
			this.tabPage2.Controls.Add(this.label6);
			this.tabPage2.Controls.Add(this.richTextBox1);
			this.tabPage2.Location = new System.Drawing.Point(4, 25);
			this.tabPage2.Name = "tabPage2";
			this.tabPage2.Padding = new System.Windows.Forms.Padding(3);
			this.tabPage2.Size = new System.Drawing.Size(621, 221);
			this.tabPage2.TabIndex = 1;
			this.tabPage2.Text = "Exception filters list";
			this.tabPage2.UseVisualStyleBackColor = true;
			// 
			// richTextBox2
			// 
			this.richTextBox2.Location = new System.Drawing.Point(458, 29);
			this.richTextBox2.Name = "richTextBox2";
			this.richTextBox2.ReadOnly = true;
			this.richTextBox2.Size = new System.Drawing.Size(155, 185);
			this.richTextBox2.TabIndex = 2;
			this.richTextBox2.Text = "NetlinkEvent, 1619\nNoPid\n, 1234";
			// 
			// label6
			// 
			this.label6.AutoSize = true;
			this.label6.Location = new System.Drawing.Point(457, 6);
			this.label6.Name = "label6";
			this.label6.Size = new System.Drawing.Size(67, 16);
			this.label6.TabIndex = 1;
			this.label6.Text = "Example:";
			// 
			// richTextBox1
			// 
			this.richTextBox1.Location = new System.Drawing.Point(6, 8);
			this.richTextBox1.Name = "richTextBox1";
			this.richTextBox1.Size = new System.Drawing.Size(446, 206);
			this.richTextBox1.TabIndex = 0;
			this.richTextBox1.Text = "";
			this.richTextBox1.WordWrap = false;
			// 
			// FormCConfig
			// 
			this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
			this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
			this.ClientSize = new System.Drawing.Size(653, 319);
			this.Controls.Add(this.tabControl1);
			this.Controls.Add(this.buttonOK);
			this.Controls.Add(this.buttonCancel);
			this.Font = new System.Drawing.Font("Verdana", 9.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedDialog;
			this.MaximizeBox = false;
			this.Name = "FormCConfig";
			this.StartPosition = System.Windows.Forms.FormStartPosition.CenterParent;
			this.Text = "Config";
			this.Load += new System.EventHandler(this.FormCConfig_Load);
			this.tabControl1.ResumeLayout(false);
			this.tabPage1.ResumeLayout(false);
			this.tabPage1.PerformLayout();
			this.tabPage2.ResumeLayout(false);
			this.tabPage2.PerformLayout();
			this.ResumeLayout(false);

		}

		#endregion

		private System.Windows.Forms.Button buttonCancel;
		private System.Windows.Forms.Button buttonOK;
		private System.Windows.Forms.TextBox textBoxSoLib;
		private System.Windows.Forms.Label label3;
		private System.Windows.Forms.TextBox textBoxPython;
		private System.Windows.Forms.Label label2;
		private System.Windows.Forms.TextBox textBoxNDK;
		private System.Windows.Forms.Label label1;
		private System.Windows.Forms.Label label5;
		private System.Windows.Forms.Button buttonNDK;
		private System.Windows.Forms.Button buttonPython;
		private System.Windows.Forms.Button buttonSo;
		private System.Windows.Forms.FolderBrowserDialog folderBrowserDialog1;
		private System.Windows.Forms.OpenFileDialog openFileDialog1;
		private System.Windows.Forms.TabControl tabControl1;
		private System.Windows.Forms.TabPage tabPage1;
		private System.Windows.Forms.TabPage tabPage2;
		private System.Windows.Forms.RichTextBox richTextBox1;
        private System.Windows.Forms.Label label6;
        private System.Windows.Forms.RichTextBox richTextBox2;
	}
}