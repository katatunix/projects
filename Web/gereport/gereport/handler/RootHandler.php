<?php

namespace gereport\handler;

__import('handler/Handler');
__import('controller/Redirector');
__import('view/UrlSource');

use gereport\controller\Redirector;
use gereport\view\UrlSource;

class RootHandler extends Handler implements Redirector, UrlSource
{
	const INDEX_RT = '';
	const LOGIN_RT = 'login';
	const LOGOUT_RT = 'logout';
	const REPORT_RT = 'report';
	const OPTIONS_RT = 'options';
	const CHANGE_PASSWORD_RT = 'cpass';

	private $map = array
	(
		self::INDEX_RT => 'IndexHandler',
		self::LOGIN_RT => 'LoginHandler',
		self::LOGOUT_RT => 'LogoutHandler',
		self::REPORT_RT => 'ReportHandler',
		self::OPTIONS_RT => 'OptionsHandler',
		self::CHANGE_PASSWORD_RT => 'ChangePasswordHandler'
	);

	private $rootUrl;

	public function __construct($rootUrl, $toolbox)
	{
		parent::__construct($toolbox);
		$this->rootUrl = $rootUrl;
	}

	public function handle()
	{
		$handlerClass = null;
		if ($this->toolbox->database->isConnected())
		{
			foreach ($this->map as $router => $class)
			{
				if ($router == $this->toolbox->request->getRouter())
				{
					$handlerClass = $class;
					break;
				}
			}
		}
		if (!$handlerClass)
		{
			$handlerClass = 'Error404Handler';
		}

		__import('handler/' . $handlerClass);
		$handlerClass = '\\gereport\\handler\\' . $handlerClass;
		(new $handlerClass($this->toolbox))->handle();
	}

	public function toIndex()
	{
		$this->redirect($this->getIndexUrl());
	}

	public function toLogout()
	{
		$this->redirect($this->getLogoutUrl());
	}

	private function redirect($url)
	{
		header('LOCATION: ' . $url);
		exit;
	}

	public function getHtmlUrl()
	{
		return $this->rootUrl . 'html/';
	}

	public function getIndexUrl()
	{
		return $this->rootUrl;
	}

	public function getLoginUrl()
	{
		return $this->rootUrl . self::LOGIN_RT;
	}

	public function getLogoutUrl()
	{
		return $this->rootUrl . self::LOGOUT_RT;
	}

	public function getReportUrl()
	{
		return $this->rootUrl . self::REPORT_RT;
	}

	public function getOptionsUrl()
	{
		return $this->rootUrl . self::OPTIONS_RT;
	}

	public function getChangePasswordUrl()
	{
		return $this->rootUrl . self::CHANGE_PASSWORD_RT;
	}
}
