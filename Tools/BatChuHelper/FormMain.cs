using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace BatChuHelper
{
	public partial class FormMain : Form
	{
		public FormMain()
		{
			InitializeComponent();
		}

		private void FormMain_Load(object sender, EventArgs e)
		{
			m_bch = new BatChuHelper_V2();
		}

		private BatChuHelper_Base m_bch;

		private void buttonGuess_Click(object sender, EventArgs e)
		{
			try
			{
				string[] p = textBoxKey.Text.Split(new char[] { ',' }, StringSplitOptions.RemoveEmptyEntries);
				string key = "";
				if (p.Length > 0)
				{
					foreach (char ch in p[0])
					{
						bool existInAll = true;
						for (int i = 1; i < p.Length; i++)
						{
							if (p[i].IndexOf(ch) == -1)
							{
								existInAll = false;
								break;
							}
						}

						if (existInAll)
						{
							key += ch;
						}
					}
				}

				textBoxKey.Text = key;

				List<string> solutions = m_bch.solve(key, textBoxPattern.Text);
				if (solutions.Count == 0)
				{
					richTextBoxResult.Text = "Không có đáp án nào!";
				}
				else
				{
					string text = "";
					for (int i = 0; i < solutions.Count; i++)
					{
						text += (i+1) + ". " + solutions[i] + Environment.NewLine;
					}
					richTextBoxResult.Text = text;
				}

				if ( !m_bch.hasSearchFull() )
				{
					MessageBox.Show(this,
						"Có quá nhiều đáp án, chỉ có thể tìm được " + solutions.Count + " đáp án đầu tiên.\n" +
						"Vui lòng mở thêm ô chữ để rút ngắn số đáp án.",
						"Warning", MessageBoxButtons.OK, MessageBoxIcon.Warning);
				}
			}
			catch (Exception ex)
			{
				MessageBox.Show(this, ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
			}
		}
	}
}
