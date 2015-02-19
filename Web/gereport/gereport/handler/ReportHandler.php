<?php

namespace gereport\handler;

__import('handler/MainLayoutHandler');
__import('controller/ReportController');
__import('view/ReportView');

use gereport\controller\ReportController;
use gereport\view\ReportView;

class ReportHandler extends MainLayoutHandler
{
	public function getContentView()
	{
		$view = new ReportView($this->toolbox->request, $this->toolbox->urlSource, $this->toolbox->htmlDir);
		return (new ReportController($view, $this->toolbox))->process();
	}
}
