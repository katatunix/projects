namespace RapeNhocConan2
{
	partial class FormProxy
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
			this.label1 = new System.Windows.Forms.Label();
			this.textBoxDomain = new System.Windows.Forms.TextBox();
			this.textBoxPort = new System.Windows.Forms.TextBox();
			this.label2 = new System.Windows.Forms.Label();
			this.textBoxUsername = new System.Windows.Forms.TextBox();
			this.label3 = new System.Windows.Forms.Label();
			this.textBoxPassword = new System.Windows.Forms.TextBox();
			this.label4 = new System.Windows.Forms.Label();
			this.checkBoxUseProxy = new System.Windows.Forms.CheckBox();
			this.panelProxy = new System.Windows.Forms.Panel();
			this.buttonOK = new System.Windows.Forms.Button();
			this.buttonCancel = new System.Windows.Forms.Button();
			this.panelProxy.SuspendLayout();
			this.SuspendLayout();
			// 
			// label1
			// 
			this.label1.AutoSize = true;
			this.label1.Location = new System.Drawing.Point(30, 15);
			this.label1.Name = "label1";
			this.label1.Size = new System.Drawing.Size(43, 13);
			this.label1.TabIndex = 0;
			this.label1.Text = "Domain";
			// 
			// textBoxDomain
			// 
			this.textBoxDomain.Location = new System.Drawing.Point(79, 12);
			this.textBoxDomain.Name = "textBoxDomain";
			this.textBoxDomain.Size = new System.Drawing.Size(182, 20);
			this.textBoxDomain.TabIndex = 1;
			// 
			// textBoxPort
			// 
			this.textBoxPort.Location = new System.Drawing.Point(79, 38);
			this.textBoxPort.Name = "textBoxPort";
			this.textBoxPort.Size = new System.Drawing.Size(182, 20);
			this.textBoxPort.TabIndex = 3;
			// 
			// label2
			// 
			this.label2.AutoSize = true;
			this.label2.Location = new System.Drawing.Point(47, 41);
			this.label2.Name = "label2";
			this.label2.Size = new System.Drawing.Size(26, 13);
			this.label2.TabIndex = 2;
			this.label2.Text = "Port";
			// 
			// textBoxUsername
			// 
			this.textBoxUsername.Location = new System.Drawing.Point(79, 64);
			this.textBoxUsername.Name = "textBoxUsername";
			this.textBoxUsername.Size = new System.Drawing.Size(182, 20);
			this.textBoxUsername.TabIndex = 5;
			// 
			// label3
			// 
			this.label3.AutoSize = true;
			this.label3.Location = new System.Drawing.Point(18, 67);
			this.label3.Name = "label3";
			this.label3.Size = new System.Drawing.Size(55, 13);
			this.label3.TabIndex = 4;
			this.label3.Text = "Username";
			// 
			// textBoxPassword
			// 
			this.textBoxPassword.Location = new System.Drawing.Point(79, 90);
			this.textBoxPassword.Name = "textBoxPassword";
			this.textBoxPassword.Size = new System.Drawing.Size(182, 20);
			this.textBoxPassword.TabIndex = 7;
			this.textBoxPassword.UseSystemPasswordChar = true;
			// 
			// label4
			// 
			this.label4.AutoSize = true;
			this.label4.Location = new System.Drawing.Point(20, 93);
			this.label4.Name = "label4";
			this.label4.Size = new System.Drawing.Size(53, 13);
			this.label4.TabIndex = 6;
			this.label4.Text = "Password";
			// 
			// checkBoxUseProxy
			// 
			this.checkBoxUseProxy.AutoSize = true;
			this.checkBoxUseProxy.Location = new System.Drawing.Point(12, 12);
			this.checkBoxUseProxy.Name = "checkBoxUseProxy";
			this.checkBoxUseProxy.Size = new System.Drawing.Size(85, 17);
			this.checkBoxUseProxy.TabIndex = 8;
			this.checkBoxUseProxy.Text = "Is Use Proxy";
			this.checkBoxUseProxy.UseVisualStyleBackColor = true;
			this.checkBoxUseProxy.CheckedChanged += new System.EventHandler(this.checkBoxUseProxy_CheckedChanged);
			// 
			// panelProxy
			// 
			this.panelProxy.Controls.Add(this.textBoxUsername);
			this.panelProxy.Controls.Add(this.label1);
			this.panelProxy.Controls.Add(this.textBoxPassword);
			this.panelProxy.Controls.Add(this.textBoxDomain);
			this.panelProxy.Controls.Add(this.label4);
			this.panelProxy.Controls.Add(this.label2);
			this.panelProxy.Controls.Add(this.textBoxPort);
			this.panelProxy.Controls.Add(this.label3);
			this.panelProxy.Location = new System.Drawing.Point(12, 35);
			this.panelProxy.Name = "panelProxy";
			this.panelProxy.Size = new System.Drawing.Size(268, 118);
			this.panelProxy.TabIndex = 9;
			// 
			// buttonOK
			// 
			this.buttonOK.Location = new System.Drawing.Point(91, 159);
			this.buttonOK.Name = "buttonOK";
			this.buttonOK.Size = new System.Drawing.Size(75, 23);
			this.buttonOK.TabIndex = 10;
			this.buttonOK.Text = "OK";
			this.buttonOK.UseVisualStyleBackColor = true;
			this.buttonOK.Click += new System.EventHandler(this.buttonOK_Click);
			// 
			// buttonCancel
			// 
			this.buttonCancel.Location = new System.Drawing.Point(172, 159);
			this.buttonCancel.Name = "buttonCancel";
			this.buttonCancel.Size = new System.Drawing.Size(75, 23);
			this.buttonCancel.TabIndex = 11;
			this.buttonCancel.Text = "Cancel";
			this.buttonCancel.UseVisualStyleBackColor = true;
			this.buttonCancel.Click += new System.EventHandler(this.buttonCancel_Click);
			// 
			// FormProxy
			// 
			this.AcceptButton = this.buttonOK;
			this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
			this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
			this.CancelButton = this.buttonCancel;
			this.ClientSize = new System.Drawing.Size(292, 191);
			this.Controls.Add(this.buttonCancel);
			this.Controls.Add(this.buttonOK);
			this.Controls.Add(this.panelProxy);
			this.Controls.Add(this.checkBoxUseProxy);
			this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.Fixed3D;
			this.MaximizeBox = false;
			this.MinimizeBox = false;
			this.Name = "FormProxy";
			this.ShowInTaskbar = false;
			this.StartPosition = System.Windows.Forms.FormStartPosition.CenterParent;
			this.Text = "Proxy";
			this.Load += new System.EventHandler(this.FormProxy_Load);
			this.panelProxy.ResumeLayout(false);
			this.panelProxy.PerformLayout();
			this.ResumeLayout(false);
			this.PerformLayout();

		}

		#endregion

		private System.Windows.Forms.Label label1;
		private System.Windows.Forms.TextBox textBoxDomain;
		private System.Windows.Forms.TextBox textBoxPort;
		private System.Windows.Forms.Label label2;
		private System.Windows.Forms.TextBox textBoxUsername;
		private System.Windows.Forms.Label label3;
		private System.Windows.Forms.TextBox textBoxPassword;
		private System.Windows.Forms.Label label4;
		private System.Windows.Forms.CheckBox checkBoxUseProxy;
		private System.Windows.Forms.Panel panelProxy;
		private System.Windows.Forms.Button buttonOK;
		private System.Windows.Forms.Button buttonCancel;
	}
}