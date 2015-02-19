<?php

namespace gereport\view;

__import('view/View');

class ChangePasswordView extends View
{
	private $oldPassword;
	private $confirmPassword;
	private $newPassword;
	private $isSuccess;
	private $message;

	public function __construct($request, $urlSource, $htmlDir)
	{
		parent::__construct($request, $urlSource, $htmlDir);
		$this->oldPassword = $this->request->isPostMethod() ? $this->request->getDataPost('oldPassword') : '';
		$this->newPassword = $this->request->isPostMethod() ? $this->request->getDataPost('newPassword') : '';
		$this->confirmPassword = $this->request->isPostMethod() ? $this->request->getDataPost('confirmPassword') : '';
	}

	public function show()
	{
		require $this->htmlDir . 'ChangePasswordHtml.php';
	}

	public function getOldPassword()
	{
		return $this->oldPassword;
	}

	public function getNewPassword()
	{
		return $this->newPassword;
	}

	public function getConfirmPassword()
	{
		return $this->confirmPassword;
	}

	public function setIsSuccess($success)
	{
		$this->isSuccess = $success;
	}

	public function setMessage($msg)
	{
		$this->message = $msg;
	}
}
