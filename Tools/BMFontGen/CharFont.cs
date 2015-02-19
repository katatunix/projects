using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BMFontGen
{
    class CharFont
    {
        public int id;
        public int x, y, width, height;
        public int xoffset, yoffset;

        public CharFont()
        {
            id = 0;
            x = y = width = height = 0;
            xoffset = yoffset = 0;
        }
    }
}
