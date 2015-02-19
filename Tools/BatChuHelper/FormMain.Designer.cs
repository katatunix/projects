namespace BatChuHelper
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
			System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(FormMain));
			this.richTextBoxResult = new System.Windows.Forms.RichTextBox();
			this.label1 = new System.Windows.Forms.Label();
			this.label2 = new System.Windows.Forms.Label();
			this.textBoxPattern = new System.Windows.Forms.TextBox();
			this.textBoxKey = new System.Windows.Forms.TextBox();
			this.buttonGuess = new System.Windows.Forms.Button();
			this.SuspendLayout();
			// 
			// richTextBoxResult
			// 
			this.richTextBoxResult.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom) 
            | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
			this.richTextBoxResult.Location = new System.Drawing.Point(15, 137);
			this.richTextBoxResult.Margin = new System.Windows.Forms.Padding(7, 6, 7, 6);
			this.richTextBoxResult.Name = "richTextBoxResult";
			this.richTextBoxResult.Size = new System.Drawing.Size(513, 361);
			this.richTextBoxResult.TabIndex = 0;
			this.richTextBoxResult.Text = "";
			// 
			// label1
			// 
			this.label1.AutoSize = true;
			this.label1.Location = new System.Drawing.Point(20, 27);
			this.label1.Margin = new System.Windows.Forms.Padding(7, 0, 7, 0);
			this.label1.Name = "label1";
			this.label1.Size = new System.Drawing.Size(73, 25);
			this.label1.TabIndex = 1;
			this.label1.Text = "Ô chữ";
			// 
			// label2
			// 
			this.label2.AutoSize = true;
			this.label2.Location = new System.Drawing.Point(27, 83);
			this.label2.Margin = new System.Windows.Forms.Padding(7, 0, 7, 0);
			this.label2.Name = "label2";
			this.label2.Size = new System.Drawing.Size(66, 25);
			this.label2.TabIndex = 2;
			this.label2.Text = "Gợi ý";
			// 
			// textBoxPattern
			// 
			this.textBoxPattern.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
			this.textBoxPattern.CharacterCasing = System.Windows.Forms.CharacterCasing.Upper;
			this.textBoxPattern.Location = new System.Drawing.Point(105, 24);
			this.textBoxPattern.Margin = new System.Windows.Forms.Padding(7, 6, 7, 6);
			this.textBoxPattern.Name = "textBoxPattern";
			this.textBoxPattern.Size = new System.Drawing.Size(252, 33);
			this.textBoxPattern.TabIndex = 3;
			this.textBoxPattern.Text = "HO***";
			// 
			// textBoxKey
			// 
			this.textBoxKey.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
			this.textBoxKey.CharacterCasing = System.Windows.Forms.CharacterCasing.Upper;
			this.textBoxKey.Location = new System.Drawing.Point(105, 80);
			this.textBoxKey.Margin = new System.Windows.Forms.Padding(7, 6, 7, 6);
			this.textBoxKey.Name = "textBoxKey";
			this.textBoxKey.Size = new System.Drawing.Size(252, 33);
			this.textBoxKey.TabIndex = 4;
			this.textBoxKey.Text = "OSEOMSSIXUXAYH";
			// 
			// buttonGuess
			// 
			this.buttonGuess.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
			this.buttonGuess.Location = new System.Drawing.Point(367, 24);
			this.buttonGuess.Name = "buttonGuess";
			this.buttonGuess.Size = new System.Drawing.Size(161, 89);
			this.buttonGuess.TabIndex = 5;
			this.buttonGuess.Text = "ĐOÁN";
			this.buttonGuess.UseVisualStyleBackColor = true;
			this.buttonGuess.Click += new System.EventHandler(this.buttonGuess_Click);
			// 
			// FormMain
			// 
			this.AutoScaleDimensions = new System.Drawing.SizeF(13F, 25F);
			this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
			this.ClientSize = new System.Drawing.Size(542, 513);
			this.Controls.Add(this.buttonGuess);
			this.Controls.Add(this.textBoxKey);
			this.Controls.Add(this.textBoxPattern);
			this.Controls.Add(this.label2);
			this.Controls.Add(this.label1);
			this.Controls.Add(this.richTextBoxResult);
			this.Font = new System.Drawing.Font("Verdana", 15.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
			this.Margin = new System.Windows.Forms.Padding(7, 6, 7, 6);
			this.Name = "FormMain";
			this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
			this.Text = "BatChu Helper - facebook.com/katatunix";
			this.Load += new System.EventHandler(this.FormMain_Load);
			this.ResumeLayout(false);
			this.PerformLayout();

		}

		#endregion

		private System.Windows.Forms.RichTextBox richTextBoxResult;
		private System.Windows.Forms.Label label1;
		private System.Windows.Forms.Label label2;
		private System.Windows.Forms.TextBox textBoxPattern;
		private System.Windows.Forms.TextBox textBoxKey;
		private System.Windows.Forms.Button buttonGuess;
	}
}

