using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace win2tiz
{
	struct TCommand
	{
		public string		command;
		public string		verboseString;
		public string		workingDir;
		public string		alias;
		public ECommandType	type;
		public bool			useMongcc;
		public string		prjName;

		public TCommand(
			string			_command,
			string			_verboseString,
			string			_workingDir,
			string			_alias,
			ECommandType	_type,
			bool			_useMongcc,
			string			_prjName)
		{
			command			= _command;
			verboseString	= _verboseString;
			workingDir		= _workingDir;
			alias			= _alias;
			type			= _type;
			useMongcc		= _useMongcc;
			prjName			= _prjName;
		}
	}
}
