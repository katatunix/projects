using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BMFontGen
{
    class FontDescriptor
    {
        public static int N = 128;

        public int number;
        public int spaceWidth;
        public int charBetween;
        public CharFont[] c;

        public FontDescriptor()
        {
            number = 0;
            spaceWidth = 0;
            charBetween = 0;
            c = new CharFont[N];
            for (int i = 0; i < N; i++)
                c[i] = new CharFont();
        }
    }
}
