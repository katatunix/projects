using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

using CELib;

namespace CoolExpression
{
	public partial class FormMain : Form
	{
		public FormMain()
		{
			InitializeComponent();
		}

		private void FormMain_Load(object sender, EventArgs e)
		{
			labelResult.Text = "";
		}

		private void buttonCalculate_Click(object sender, EventArgs e)
		{
			Calculator calculator;
			try
			{
				calculator = new Calculator(textBoxExpression.Text);
			}
			catch (Exception)
			{
				MessageBox.Show(this, "Invalid expression", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
				return;
			}

			try
			{
				labelResult.Text = "Result: " + calculator.calculate(textBoxSolution.Text).ToString();
			}
			catch (Exception)
			{
				MessageBox.Show(this, "Invalid solution", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
			}
		}
	}
}
