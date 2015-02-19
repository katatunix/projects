<?php

namespace gereport\transaction;

use gereport\domainproxy\MemberProxy;

__import('transaction/Transaction');

class ChangePasswordTransaction extends Transaction
{
	private $memberId, $oldPassword, $newPassword, $confirmPassword;

	public function __construct($memberId, $oldPassword, $newPassword, $confirmPassword, $database)
	{
		parent::__construct($database);
		$this->memberId = $memberId;
		$this->oldPassword = $oldPassword;
		$this->newPassword = $newPassword;
		$this->confirmPassword = $confirmPassword;
	}

	public function execute()
	{
		if (!$this->oldPassword)
		{
			throw new \Exception('The current password must not be empty!');
		}
		if (!$this->newPassword)
		{
			throw new \Exception('The new password must not be empty!');
		}
		if (!$this->confirmPassword)
		{
			throw new \Exception('The confirm password must not be empty!');
		}
		if ($this->newPassword != $this->confirmPassword)
		{
			throw new \Exception('The current and confirm password are not matched!');
		}

		if ( !$this->database->hasMember($this->memberId) )
		{
			throw new \Exception('The member is not existed!');
		}

		$member = new MemberProxy($this->memberId, $this->database);
		if ( !$member->hasPassword($this->oldPassword) )
		{
			throw new \Exception('The current password is wrong!');
		}

		$member->changePassword($this->newPassword);
	}
}
