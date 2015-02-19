namespace PLog
{
	partial class FormStackTraceOutput
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
            this.fastColoredTextBoxStackTrace = new FastColoredTextBoxNS.FastColoredTextBox();
            this.buttonClose = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // fastColoredTextBoxStackTrace
            // 
            this.fastColoredTextBoxStackTrace.AutoScrollMinSize = new System.Drawing.Size(25, 15);
            this.fastColoredTextBoxStackTrace.BackBrush = null;
            this.fastColoredTextBoxStackTrace.Cursor = System.Windows.Forms.Cursors.IBeam;
            this.fastColoredTextBoxStackTrace.DisabledColor = System.Drawing.Color.FromArgb(((int)(((byte)(100)))), ((int)(((byte)(180)))), ((int)(((byte)(180)))), ((int)(((byte)(180)))));
            this.fastColoredTextBoxStackTrace.Location = new System.Drawing.Point(15, 15);
            this.fastColoredTextBoxStackTrace.Name = "fastColoredTextBoxStackTrace";
            this.fastColoredTextBoxStackTrace.Paddings = new System.Windows.Forms.Padding(0);
            this.fastColoredTextBoxStackTrace.SelectionColor = System.Drawing.Color.FromArgb(((int)(((byte)(50)))), ((int)(((byte)(0)))), ((int)(((byte)(0)))), ((int)(((byte)(255)))));
            this.fastColoredTextBoxStackTrace.Size = new System.Drawing.Size(795, 330);
            this.fastColoredTextBoxStackTrace.TabIndex = 0;
            // 
            // buttonClose
            // 
            this.buttonClose.Location = new System.Drawing.Point(363, 355);
            this.buttonClose.Name = "buttonClose";
            this.buttonClose.Size = new System.Drawing.Size(99, 29);
            this.buttonClose.TabIndex = 1;
            this.buttonClose.Text = "Close";
            this.buttonClose.UseVisualStyleBackColor = true;
            this.buttonClose.Click += new System.EventHandler(this.buttonClose_Click);
            // 
            // FormStackTraceOutput
            // 
            this.AcceptButton = this.buttonClose;
            this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(825, 395);
            this.Controls.Add(this.buttonClose);
            this.Controls.Add(this.fastColoredTextBoxStackTrace);
            this.Font = new System.Drawing.Font("Verdana", 9.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedDialog;
            this.MaximizeBox = false;
            this.Name = "FormStackTraceOutput";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterParent;
            this.Text = "Stack Trace Output";
            this.ResumeLayout(false);

		}

		#endregion

		private FastColoredTextBoxNS.FastColoredTextBox fastColoredTextBoxStackTrace;
		private System.Windows.Forms.Button buttonClose;
	}
}