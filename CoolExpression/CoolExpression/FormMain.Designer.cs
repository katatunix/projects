namespace CoolExpression
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
			this.textBoxExpression = new System.Windows.Forms.TextBox();
			this.label1 = new System.Windows.Forms.Label();
			this.label2 = new System.Windows.Forms.Label();
			this.textBoxSolution = new System.Windows.Forms.TextBox();
			this.buttonCalculate = new System.Windows.Forms.Button();
			this.labelResult = new System.Windows.Forms.Label();
			this.SuspendLayout();
			// 
			// textBoxExpression
			// 
			this.textBoxExpression.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
			this.textBoxExpression.Font = new System.Drawing.Font("Microsoft Sans Serif", 15.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.textBoxExpression.Location = new System.Drawing.Point(138, 12);
			this.textBoxExpression.Name = "textBoxExpression";
			this.textBoxExpression.Size = new System.Drawing.Size(494, 31);
			this.textBoxExpression.TabIndex = 0;
			this.textBoxExpression.Text = "1+2-3*4/sin(-x)+cos(y)-tan(x)*(7+sqrt(x))/pow(x, y)";
			// 
			// label1
			// 
			this.label1.AutoSize = true;
			this.label1.Font = new System.Drawing.Font("Microsoft Sans Serif", 15.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label1.Location = new System.Drawing.Point(13, 15);
			this.label1.Name = "label1";
			this.label1.Size = new System.Drawing.Size(119, 25);
			this.label1.TabIndex = 1;
			this.label1.Text = "Expression";
			// 
			// label2
			// 
			this.label2.AutoSize = true;
			this.label2.Font = new System.Drawing.Font("Microsoft Sans Serif", 15.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.label2.Location = new System.Drawing.Point(42, 55);
			this.label2.Name = "label2";
			this.label2.Size = new System.Drawing.Size(90, 25);
			this.label2.TabIndex = 3;
			this.label2.Text = "Solution";
			// 
			// textBoxSolution
			// 
			this.textBoxSolution.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
			this.textBoxSolution.Font = new System.Drawing.Font("Microsoft Sans Serif", 15.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.textBoxSolution.Location = new System.Drawing.Point(138, 52);
			this.textBoxSolution.Name = "textBoxSolution";
			this.textBoxSolution.Size = new System.Drawing.Size(494, 31);
			this.textBoxSolution.TabIndex = 2;
			this.textBoxSolution.Text = "x=1,y=2";
			// 
			// buttonCalculate
			// 
			this.buttonCalculate.Font = new System.Drawing.Font("Microsoft Sans Serif", 15.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.buttonCalculate.Location = new System.Drawing.Point(138, 101);
			this.buttonCalculate.Name = "buttonCalculate";
			this.buttonCalculate.Size = new System.Drawing.Size(122, 38);
			this.buttonCalculate.TabIndex = 4;
			this.buttonCalculate.Text = "Calculate";
			this.buttonCalculate.UseVisualStyleBackColor = true;
			this.buttonCalculate.Click += new System.EventHandler(this.buttonCalculate_Click);
			// 
			// labelResult
			// 
			this.labelResult.AutoSize = true;
			this.labelResult.Font = new System.Drawing.Font("Microsoft Sans Serif", 15.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
			this.labelResult.ForeColor = System.Drawing.Color.Blue;
			this.labelResult.Location = new System.Drawing.Point(266, 108);
			this.labelResult.Name = "labelResult";
			this.labelResult.Size = new System.Drawing.Size(90, 25);
			this.labelResult.TabIndex = 5;
			this.labelResult.Text = "Solution";
			// 
			// FormMain
			// 
			this.AcceptButton = this.buttonCalculate;
			this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
			this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
			this.ClientSize = new System.Drawing.Size(644, 160);
			this.Controls.Add(this.labelResult);
			this.Controls.Add(this.buttonCalculate);
			this.Controls.Add(this.label2);
			this.Controls.Add(this.textBoxSolution);
			this.Controls.Add(this.label1);
			this.Controls.Add(this.textBoxExpression);
			this.Name = "FormMain";
			this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
			this.Text = "Cool Expression © Katatunix";
			this.Load += new System.EventHandler(this.FormMain_Load);
			this.ResumeLayout(false);
			this.PerformLayout();

		}

		#endregion

		private System.Windows.Forms.TextBox textBoxExpression;
		private System.Windows.Forms.Label label1;
		private System.Windows.Forms.Label label2;
		private System.Windows.Forms.TextBox textBoxSolution;
		private System.Windows.Forms.Button buttonCalculate;
		private System.Windows.Forms.Label labelResult;
	}
}

