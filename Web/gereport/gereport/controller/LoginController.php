<?php

namespace gereport\controller;

__import('controller/Controller');
__import('transaction/LoginTransaction');
__import('view/LoginView');
__import('session/Session');

use gereport\session\Session;
use gereport\transaction\LoginTransaction;
use gereport\view\LoginView;

class LoginController extends Controller
{
	/**
	 * @var LoginView
	 */
	private $loginView;

	public function __construct($loginView, $toolbox)
	{
		parent::__construct($toolbox);
		$this->loginView = $loginView;
	}

	public function process()
	{
		if ($this->loginView->isPostMethod())
		{
			$username = $this->loginView->getUsername();
			$password = $this->loginView->getPassword();

			$transaction = new LoginTransaction($username, $password, $this->toolbox->database);
			$transaction->execute();

			$loggedMemberId = $transaction->getLoggedMemberId();

			if ($loggedMemberId > Session::NO_MEMBER_ID)
			{
				$this->toolbox->session->setLoggedMemberId($loggedMemberId);
				$this->toolbox->redirector->toIndex();
			}
			else
			{
				$this->loginView->setUsername($username);
				$this->loginView->setMessage('Login failed!');
			}
		}
		$this->loginView->setTitle('Login to the Hell');
		return $this->loginView;
	}
}
