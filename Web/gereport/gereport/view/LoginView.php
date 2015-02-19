<?php

namespace gereport\view;

__import('view/View');

class LoginView extends View
{
	private $username;
	private $password;
	private $message;

	public function __construct($request, $urlSource, $htmlDir)
	{
		parent::__construct($request, $urlSource, $htmlDir);
		$this->username = $this->isPostMethod() ? $this->request->getDataPost('username') : '';
		$this->password = $this->isPostMethod() ? $this->request->getDataPost('password') : '';
		$this->message = '';
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setUsername($val)
	{
		$this->username = $val;
	}

	public function setMessage($val)
	{
		$this->message = $val;
	}

	public function show()
	{
		require $this->htmlDir . 'LoginHtml.php';
	}
}
