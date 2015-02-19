using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Xml;

namespace BMFontGen
{
	public partial class Form1 : Form
	{
		String str = "0123456789.,:!?()-'/ABCDEFGHIJKLMNOPQRSTUVWXYZÁÀẢÃẠĂẮẰẲẴẶÂẤẦẨẪẬÉÈẺẼẸÊẾỀỂỄỆÍÌỈĨỊÓÒỎÕỌÔỐỒỔỖỘƠỚỜỞỠỢÚÙỦŨỤƯỨỪỬỮỰÝỲỶỸỴĐ";

		public Form1()
		{
			InitializeComponent();
		}

		private int convert(int unicode)
		{
			char c = (char) unicode;
			return str.IndexOf(c);
		}

		private void Form1_Load(object sender, EventArgs e)
		{
			
		}

        private void button1_Click(object sender, EventArgs e)
        {
            DialogResult res = openFileDialog1.ShowDialog();
            if (res != DialogResult.OK)
                return;

            String descFile = openFileDialog1.FileName;

            FontDescriptor fontDesc = new FontDescriptor();
            fontDesc.number = 113;
            fontDesc.spaceWidth = 4;
            fontDesc.charBetween = 3;

            XmlDocument doc = new XmlDocument();
            doc.Load(descFile);
            XmlNodeList list = doc.GetElementsByTagName("char");

            int i = 0;
            foreach (XmlNode node in list)
            {
                String id = node.Attributes["id"].Value;
                String x = node.Attributes["x"].Value;
                String y = node.Attributes["y"].Value;
                String width = node.Attributes["width"].Value;
                String height = node.Attributes["height"].Value;
                String xoffset = node.Attributes["xoffset"].Value;
                String yoffset = node.Attributes["yoffset"].Value;

                fontDesc.c[i].id = convert(int.Parse(id));
                fontDesc.c[i].x = int.Parse(x);
                fontDesc.c[i].y = int.Parse(y);
                fontDesc.c[i].width = int.Parse(width);
                fontDesc.c[i].height = int.Parse(height);
                fontDesc.c[i].xoffset = int.Parse(xoffset);
                fontDesc.c[i].yoffset = int.Parse(yoffset);
                i++;
            }

            for (int j = 0; j < i - 1; j++)
            {
                for (int k = j + 1; k < i; k++)
                {
                    if (fontDesc.c[j].id > fontDesc.c[k].id)
                    {
                        CharFont t = fontDesc.c[j];
                        fontDesc.c[j] = fontDesc.c[k];
                        fontDesc.c[k] = t;
                    }
                }
            }

            String rich = "{\n\t" + fontDesc.number + ", " +
                    fontDesc.spaceWidth + ", " + fontDesc.charBetween + ",\n\t{\n";

            for (int j = 0; j < i; j++)
            {
                CharFont f = fontDesc.c[j];
                rich += "\t\t{" + f.x + ", " + f.y + ", " + f.width + ", " + f.height +
                        ", " + f.xoffset + ", " + f.yoffset + "},\n";
            }

            rich += "\t}\n};";

            richTextBox1.Text = rich;
        }
	}
    
}
