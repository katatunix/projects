<?php

namespace gereport\transaction;

__import('transaction/Transaction');

class LoginTransaction extends Transaction
{
	private $username;
	private $password;
	private $loggedMemberId;

	public function __construct($username, $password, $database)
	{
		parent::__construct($database);
		$this->username = $username;
		$this->password = $password;
	}

	public function execute()
	{
		$this->loggedMemberId = $this->database->findMemberByLogin($this->username, $this->password);
	}

	public function getLoggedMemberId()
	{
		return $this->loggedMemberId;
	}
}
