<?php

namespace gereport\handler;

__import('handler/MainLayoutHandler');
__import('view/LoginView');
__import('controller/LoginController');

use gereport\controller\LoginController;
use gereport\view\LoginView;

class LoginHandler extends MainLayoutHandler
{
	public function handle()
	{
		if ($this->toolbox->session->isLogged())
		{
			$this->toolbox->redirector->toIndex();
		}
		else
		{
			parent::handle();
		}
	}

	public function getContentView()
	{
		$view = new LoginView($this->toolbox->request, $this->toolbox->urlSource, $this->toolbox->htmlDir);
		return (new LoginController($view, $this->toolbox))->process();
	}
}
