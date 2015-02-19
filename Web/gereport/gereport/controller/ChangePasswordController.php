<?php

namespace gereport\controller;

__import('session/Session');
__import('view/ChangePasswordView');
__import('controller/Controller');
__import('transaction/ChangePasswordTransaction');

use gereport\transaction\ChangePasswordTransaction;
use gereport\view\ChangePasswordView;
use gereport\view\Error403View;

class ChangePasswordController extends Controller
{
	/**
	 * @var ChangePasswordView
	 */
	private $changePasswordView;

	public function __construct($changePasswordView, $toolbox)
	{
		parent::__construct($toolbox);
		$this->changePasswordView = $changePasswordView;
	}

	public function process()
	{
		if (!$this->toolbox->session->isLogged())
		{
			return new Error403View($this->toolbox->request, $this->toolbox->urlSource, $this->toolbox->htmlDir);
		}

		if ($this->changePasswordView->isPostMethod())
		{
			$msg = null;
			$success = true;
			$tx = new ChangePasswordTransaction(
				$this->toolbox->session->getLoggedMemberId(),
				$this->changePasswordView->getOldPassword(),
				$this->changePasswordView->getNewPassword(),
				$this->changePasswordView->getConfirmPassword(),
				$this->toolbox->database);
			try
			{
				$tx->execute();
				$msg = 'Password was changed OK';
				$success = true;
			}
			catch (\Exception $ex)
			{
				$msg = $ex->getMessage();
				$success = false;
			}

			$this->changePasswordView->setMessage($msg);
			$this->changePasswordView->setIsSuccess($success);
		}

		$this->changePasswordView->setTitle('Change password');

		return $this->changePasswordView;
	}
}
