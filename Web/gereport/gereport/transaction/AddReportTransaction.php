<?php

namespace gereport\transaction;

__import('transaction/Transaction');

class AddReportTransaction extends Transaction
{
	private $memberId, $projectId, $dateFor, $datetimeAdd, $content;

	public function __construct($memberId, $projectId, $dateFor, $datetimeAdd, $content, $database)
	{
		parent::__construct($database);
		$this->memberId		= $memberId;
		$this->projectId	= $projectId;
		$this->dateFor		= $dateFor;
		$this->datetimeAdd	= $datetimeAdd;
		$this->content		= $content;
	}

	public function execute()
	{
		if ( !$this->database->hasMember($this->memberId) )
		{
			throw new \Exception('The member is not existed!');
		}

		if ( !$this->database->hasProject($this->projectId) )
		{
			throw new \Exception('The project is not existed!');
		}

		if ( !$this->database->isMemberWorkingForProject($this->memberId, $this->projectId) )
		{
			throw new \Exception('The member is not working for the project!');
		}

		if (!$this->content || !trim($this->content))
		{
			throw new \Exception('The report content must not be empty!');
		}

		$this->database->insertReport($this->memberId, $this->projectId,
			$this->dateFor, $this->datetimeAdd, $this->content);
	}
}
