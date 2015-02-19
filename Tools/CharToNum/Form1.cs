using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace CharToNum
{
    public partial class Form1 : Form
    {
        static string s = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            string t = textBox1.Text.ToUpper();
            string res = "";
            for (int i = 0; i < t.Length; i++)
            {
                int j = s.IndexOf(t[i]) + 1;
                res += j.ToString();
            }
            textBox2.Text = res;
        }
    }
}
