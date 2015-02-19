<?php

namespace gereport\transaction;

__import('transaction/Transaction');
__import('domainproxy/MemberProxy');

use gereport\domainproxy\MemberProxy;

class GetUsernameTransaction extends Transaction
{
	private $memberId;
	private $memberUsername;

	public function __construct($memberId, $database)
	{
		parent::__construct($database);
		$this->memberId = $memberId;
	}

	public function execute()
	{
		if (!$this->database->hasMember($this->memberId))
		{
			throw new \Exception('Not found the member!');
		}
		$this->memberUsername = (new MemberProxy($this->memberId, $this->database))->getUsername();
	}

	public function getMemberUsername()
	{
		return $this->memberUsername;
	}
}
