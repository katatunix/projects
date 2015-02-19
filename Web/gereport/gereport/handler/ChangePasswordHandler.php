<?php

namespace gereport\handler;

__import('handler/MainLayoutHandler');
__import('view/ChangePasswordView');
__import('view/Error403View');
__import('controller/ChangePasswordController');

use gereport\controller\ChangePasswordController;
use gereport\view\ChangePasswordView;
use gereport\view\Error403View;

class ChangePasswordHandler extends MainLayoutHandler
{
	public function getContentView()
	{
		$view = new ChangePasswordView($this->toolbox->request, $this->toolbox->urlSource, $this->toolbox->htmlDir);
		return (new ChangePasswordController($view, $this->toolbox))->process();
	}
}
