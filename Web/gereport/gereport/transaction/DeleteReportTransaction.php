<?php

namespace gereport\transaction;

__import('transaction/Transaction');

class DeleteReportTransaction extends Transaction
{
	private $reportId;

	public function __construct($reportId, $database)
	{
		parent::__construct($database);
		$this->reportId = $reportId;
	}

	public function execute()
	{
		$this->database->deleteReport($this->reportId);
	}
}
