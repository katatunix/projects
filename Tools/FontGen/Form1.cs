using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace FontGen
{
	public partial class Form1 : Form
	{
		String str = "0123456789.,:!?()-'/ABCDEFGHIJKLMNOPQRSTUVWXYZÁÀẢÃẠĂẮẰẲẴẶÂẤẦẨẪẬÉÈẺẼẸÊẾỀỂỄỆÍÌỈĨỊÓÒỎÕỌÔỐỒỔỖỘƠỚỜỞỠỢÚÙỦŨỤƯỨỪỬỮỰÝỲỶỸỴĐ";
		
		public Form1()
		{
			InitializeComponent();
		}

		private void button1_Click(object sender, EventArgs e)
		{
			String txt = textBox1.Text.ToUpper();
			String res = "{";
			for (int i = 0; i < txt.Length; i++)
			{
				int index = str.IndexOf(txt[i]);
				res += index + ", ";
			}
			res += " -2}";
			richTextBox1.Text = res;
		}
	}
}
