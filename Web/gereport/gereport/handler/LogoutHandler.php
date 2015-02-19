<?php

namespace gereport\handler;

use gereport\controller\LogoutController;

__import('handler/Handler');
__import('controller/LogoutController');

class LogoutHandler extends Handler
{
	public function handle()
	{
		(new LogoutController($this->toolbox))->process();
	}
}
