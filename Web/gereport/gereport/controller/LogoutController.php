<?php

namespace gereport\controller;

__import('controller/controller');

class LogoutController extends Controller
{
	public function __construct($toolbox)
	{
		parent::__construct($toolbox);
	}

	public function process()
	{
		$this->toolbox->session->clearLogged();
		$this->toolbox->redirector->toIndex();
	}
}
