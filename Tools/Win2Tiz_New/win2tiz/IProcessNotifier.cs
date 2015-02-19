namespace win2tiz
{
	/// <summary>
	/// Depend on:
	/// TProcessResult
	/// </summary>
	interface ICompileNotifier
	{
		void onFinishCompile(TCommand cmd, TProcessResult pr);
	}
}
