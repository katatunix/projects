<?php

namespace gereport\handler;

__import('handler/MainLayoutHandler');
__import('view/IndexView');
__import('controller/IndexController');

use gereport\controller\IndexController;
use gereport\view\IndexView;

class IndexHandler extends MainLayoutHandler
{
	public function getContentView()
	{
		$view = new IndexView($this->toolbox->request, $this->toolbox->urlSource, $this->toolbox->htmlDir);
		return (new IndexController($view, $this->toolbox))->process();
	}
}
