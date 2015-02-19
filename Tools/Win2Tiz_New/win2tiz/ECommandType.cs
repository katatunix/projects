using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace win2tiz
{
	enum ECommandType
	{
		eCompile = 0,
		eLinkStatic,
		eLinkDynamic,
		eGenerateDsym,
		eStrip,
		eCopy
	}
}
