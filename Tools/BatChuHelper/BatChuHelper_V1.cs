using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace BatChuHelper
{
	class BatChuHelper_V1 : BatChuHelper_Base
	{

		public BatChuHelper_V1()
		{
			m_solutions = new List<string>();
		}

		public override List<string> solve(string key, string pattern)
		{
			const string err = "Bạn phải nhập vào [Ô chữ] và [Gợi ý] hợp lệ!";
			if (key == null || pattern == null)
			{
				throw new ArgumentNullException(err);
			}

			key = key.Trim().ToLower();
			pattern = pattern.Trim().ToLower();
			if (key == "" || pattern == "")
			{
				throw new ArgumentException(err);
			}

			m_key = key;
			m_solutions.Clear();

			// Prepare
			foreach (char ch in pattern)
			{
				if (ch != s_kHiddenChar)
				{
					int j = m_key.IndexOf(ch);
					if (j == -1) return m_solutions;
					m_key = m_key.Remove(j, 1);
				}
			}

			// Copy pattern to m_tryingChar
			m_len = pattern.Length;
			m_tryingChar = new char[m_len];
			for (int i = 0; i < m_len; i++)
			{
				m_tryingChar[i] = pattern[i];
			}

			m_tryingIsEndWord = new bool[m_len];

			// Process
			backtrack(0);

			return m_solutions;
		}

		private void backtrack(int index)
		{
			bool useKey = m_tryingChar[index] == s_kHiddenChar;
			string candidates = useKey ? m_key : ("" + m_tryingChar[index]);

			candidates = sort( removeDuplicate(candidates) );

			foreach (char candidateChar in candidates)
			{
				// index is the end of word
				{
					string word = "" + candidateChar;
					int last = index - 1;
					while (last >= 0 && !m_tryingIsEndWord[last])
					{
						word = m_tryingChar[last] + word;
						last--;
					}

					if (VN.validWord(word))
					{
						char oldChar = m_tryingChar[index];
						m_tryingChar[index] = candidateChar;
						m_tryingIsEndWord[index] = true;
						if (useKey)
							m_key = m_key.Remove(m_key.IndexOf(candidateChar), 1);

						if (index == m_len - 1)
						{
							addSolution();
						}
						else if (m_solutions.Count < s_kMaxSolutions)
						{
							backtrack(index + 1);
						}

						m_tryingChar[index] = oldChar;
						if (useKey)
							m_key += candidateChar;
					}
				}

				// index is not the end of word
				if (index < m_len - 1)
				{
					char oldChar = m_tryingChar[index];
					m_tryingChar[index] = candidateChar;
					m_tryingIsEndWord[index] = false;
					if (useKey)
						m_key = m_key.Remove(m_key.IndexOf(candidateChar), 1);

					if (m_solutions.Count < s_kMaxSolutions)
					{
						backtrack(index + 1);
					}

					m_tryingChar[index] = oldChar;
					if (useKey)
						m_key += candidateChar;
				}

			}
		}

		private string removeDuplicate(string s)
		{
			int len = s.Length;
			bool[] mark = new bool[len];
			for (int i = 0; i < len; i++) mark[i] = true;

			for (int i = 0; i < len - 1; i++) if (mark[i])
			{
				for (int j = i + 1; j < len; j++)
				{
					if (s[i] == s[j])
					{
						mark[j] = false;
					}
				}
			}

			string res = "";
			for (int i = 0; i < len; i++)
			{
				if (mark[i])
				{
					res += s[i];
				}
			}

			return res;
		}

		private void addSolution()
		{
			string sln = "";
			for (int i = 0; i < m_len; i++)
			{
				sln += m_tryingChar[i];
				if (i < m_len - 1 && m_tryingIsEndWord[i])
				{
					sln += "  ";
				}
			}
			m_solutions.Add(sln);
		}

		private string sort(string str)
		{
			return String.Concat(str.OrderBy(c => c));
		}

		private static readonly char s_kHiddenChar = '*';
		private static readonly int s_kMaxSolutions = 20000;

		private string m_key;

		private char[] m_tryingChar;
		private bool[] m_tryingIsEndWord;
		private int m_len;

		private List<string> m_solutions;


		public override bool hasSearchFull()
		{
			return m_solutions.Count <= s_kMaxSolutions;
		}
	}
}
