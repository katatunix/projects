namespace win2tiz.visualc
{
	/// <summary>
	/// Depend on:
	/// ASolution
	/// CSolution2008
	/// </summary>
	public class CFactory
	{
		public static ASolution createSolution()
		{
			return new CSolution2008();
		}
	}
}
