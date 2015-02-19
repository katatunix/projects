<?php

namespace gereport\handler;

__import('handler/MainLayoutHandler');
__import('view/Error404View');

use gereport\view\Error404View;

class Error404Handler extends MainLayoutHandler
{
	public function getContentView()
	{
		return new Error404View($this->toolbox->request, $this->toolbox->urlSource, $this->toolbox->htmlDir);
	}
}
