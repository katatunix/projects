namespace PLog
{
	partial class FormAddFilter
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
			this.labelError = new System.Windows.Forms.Label();
			this.buttonAddFilterOK = new System.Windows.Forms.Button();
			this.label1 = new System.Windows.Forms.Label();
			this.textBoxTag = new System.Windows.Forms.TextBox();
			this.buttonCancel = new System.Windows.Forms.Button();
			this.label2 = new System.Windows.Forms.Label();
			this.label3 = new System.Windows.Forms.Label();
			this.textBoxPid = new System.Windows.Forms.TextBox();
			this.label4 = new System.Windows.Forms.Label();
			this.label5 = new System.Windows.Forms.Label();
			this.textBoxName = new System.Windows.Forms.TextBox();
			this.SuspendLayout();
			// 
			// labelError
			// 
			this.labelError.AutoSize = true;
			this.labelError.Font = new System.Drawing.Font("Verdana", 8.25F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(163)));
			this.labelError.ForeColor = System.Drawing.Color.Red;
			this.labelError.Location = new System.Drawing.Point(107, 41);
			this.labelError.Name = "labelError";
			this.labelError.Size = new System.Drawing.Size(0, 13);
			this.labelError.TabIndex = 7;
			// 
			// buttonAddFilterOK
			// 
			this.buttonAddFilterOK.Location = new System.Drawing.Point(157, 141);
			this.buttonAddFilterOK.Name = "buttonAddFilterOK";
			this.buttonAddFilterOK.Size = new System.Drawing.Size(99, 29);
			this.buttonAddFilterOK.TabIndex = 3;
			this.buttonAddFilterOK.Text = "OK";
			this.buttonAddFilterOK.UseVisualStyleBackColor = true;
			this.buttonAddFilterOK.Click += new System.EventHandler(this.buttonAddFilterOK_Click);
			// 
			// label1
			// 
			this.label1.AutoSize = true;
			this.label1.Location = new System.Drawing.Point(22, 72);
			this.label1.Name = "label1";
			this.label1.Size = new System.Drawing.Size(82, 16);
			this.label1.TabIndex = 14;
			this.label1.Text = "By Log Tag";
			// 
			// textBoxTag
			// 
			this.textBoxTag.Location = new System.Drawing.Point(110, 69);
			this.textBoxTag.Name = "textBoxTag";
			this.textBoxTag.Size = new System.Drawing.Size(302, 23);
			this.textBoxTag.TabIndex = 1;
			// 
			// buttonCancel
			// 
			this.buttonCancel.Location = new System.Drawing.Point(264, 141);
			this.buttonCancel.Name = "buttonCancel";
			this.buttonCancel.Size = new System.Drawing.Size(99, 29);
			this.buttonCancel.TabIndex = 4;
			this.buttonCancel.Text = "Cancel";
			this.buttonCancel.UseVisualStyleBackColor = true;
			this.buttonCancel.Click += new System.EventHandler(this.buttonCancel_Click);
			// 
			// label2
			// 
			this.label2.AutoSize = true;
			this.label2.ForeColor = System.Drawing.Color.Red;
			this.label2.Location = new System.Drawing.Point(85, 103);
			this.label2.Name = "label2";
			this.label2.Size = new System.Drawing.Size(0, 16);
			this.label2.TabIndex = 11;
			// 
			// label3
			// 
			this.label3.AutoSize = true;
			this.label3.Location = new System.Drawing.Point(53, 103);
			this.label3.Name = "label3";
			this.label3.Size = new System.Drawing.Size(51, 16);
			this.label3.TabIndex = 15;
			this.label3.Text = "By PID";
			// 
			// textBoxPid
			// 
			this.textBoxPid.Location = new System.Drawing.Point(110, 100);
			this.textBoxPid.Name = "textBoxPid";
			this.textBoxPid.Size = new System.Drawing.Size(302, 23);
			this.textBoxPid.TabIndex = 2;
			// 
			// label4
			// 
			this.label4.AutoSize = true;
			this.label4.ForeColor = System.Drawing.Color.Red;
			this.label4.Location = new System.Drawing.Point(85, 16);
			this.label4.Name = "label4";
			this.label4.Size = new System.Drawing.Size(0, 16);
			this.label4.TabIndex = 14;
			// 
			// label5
			// 
			this.label5.AutoSize = true;
			this.label5.Location = new System.Drawing.Point(23, 16);
			this.label5.Name = "label5";
			this.label5.Size = new System.Drawing.Size(81, 16);
			this.label5.TabIndex = 13;
			this.label5.Text = "Filter name";
			// 
			// textBoxName
			// 
			this.textBoxName.Location = new System.Drawing.Point(110, 13);
			this.textBoxName.Name = "textBoxName";
			this.textBoxName.Size = new System.Drawing.Size(302, 23);
			this.textBoxName.TabIndex = 0;
			// 
			// FormAddFilter
			// 
			this.AcceptButton = this.buttonAddFilterOK;
			this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
			this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
			this.ClientSize = new System.Drawing.Size(435, 185);
			this.Controls.Add(this.label4);
			this.Controls.Add(this.label5);
			this.Controls.Add(this.textBoxName);
			this.Controls.Add(this.label2);
			this.Controls.Add(this.label3);
			this.Controls.Add(this.textBoxPid);
			this.Controls.Add(this.buttonCancel);
			this.Controls.Add(this.labelError);
			this.Controls.Add(this.buttonAddFilterOK);
			this.Controls.Add(this.label1);
			this.Controls.Add(this.textBoxTag);
			this.Font = new System.Drawing.Font("Verdana", 9.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(163)));
			this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedDialog;
			this.MaximizeBox = false;
			this.Name = "FormAddFilter";
			this.StartPosition = System.Windows.Forms.FormStartPosition.CenterParent;
			this.Text = "New filter";
			this.ResumeLayout(false);
			this.PerformLayout();

		}

		#endregion

		private System.Windows.Forms.Label labelError;
		private System.Windows.Forms.Button buttonAddFilterOK;
		private System.Windows.Forms.Label label1;
		private System.Windows.Forms.TextBox textBoxTag;
		private System.Windows.Forms.Button buttonCancel;
		private System.Windows.Forms.Label label2;
		private System.Windows.Forms.Label label3;
		private System.Windows.Forms.TextBox textBoxPid;
		private System.Windows.Forms.Label label4;
		private System.Windows.Forms.Label label5;
		private System.Windows.Forms.TextBox textBoxName;
	}
}