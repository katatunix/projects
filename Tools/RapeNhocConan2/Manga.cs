using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace RapeNhocConan2
{
	class Manga
	{
		public static int MAX_CHAPTER_NUMBER = 1000;
		public String m_Name;
		public int m_ChapterNumber;
		public String[] m_ChapterNameList;

		public Manga()
		{
			m_Name = null;
			m_ChapterNumber = 0;
			m_ChapterNameList = new String[MAX_CHAPTER_NUMBER];
			for (int i = 0; i < MAX_CHAPTER_NUMBER; i++)
			{
				m_ChapterNameList[i] = null;
			}
		}
	}
}
