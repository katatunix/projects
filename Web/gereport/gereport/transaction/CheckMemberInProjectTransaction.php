<?php

namespace gereport\transaction;

__import('transaction/Transaction');

class CheckMemberInProjectTransaction extends Transaction
{
	private $memberId;
	private $projectId;
	private $result;

	public function __construct($memberId, $projectId, $database)
	{
		parent::__construct($database);
		$this->memberId = $memberId;
		$this->projectId = $projectId;
		$this->result = false;
	}

	public function execute()
	{
		$this->result = $this->database->isMemberWorkingForProject($this->memberId, $this->projectId);
	}

	public function isMemberInProject()
	{
		return $this->result;
	}
}
