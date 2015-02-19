<?php

namespace gereport\transaction;

__import('transaction/Transaction');
__import('domainproxy/ProjectProxy');
__import('domainproxy/ReportProxy');
__import('utils/DatetimeUtils');

use gereport\domainproxy\MemberProxy;
use gereport\domainproxy\ProjectProxy;
use gereport\domainproxy\ReportProxy;
use gereport\utils\DatetimeUtils;

class GetReportsTransaction extends Transaction
{
	private $projectId;
	private $date;
	private $callerId;

	private $projectName;
	private $reports;
	private $notReportedMembers;

	public function __construct($projectId, $date, $callerId, $database)
	{
		parent::__construct($database);
		$this->projectId = $projectId;
		$this->date = $date;
		$this->callerId = $callerId;
	}

	public function execute()
	{
		if (!$this->database->hasProject($this->projectId))
		{
			throw new \Exception('Not found the project!');
		}

		$this->projectName = (new ProjectProxy($this->projectId, $this->database))->getName();

		if (!$this->date) $this->date = DatetimeUtils::getCurDate();

		$caller = null;
		if ($this->callerId) $caller = new MemberProxy($this->callerId, $this->database);

		$this->reports = array();
		foreach ($this->database->findReportsByProjectAndDate($this->projectId, $this->date) as $reportId)
		{
			$report = new ReportProxy($reportId, $this->database);
			$this->reports[] = array
			(
				'id' => $reportId,
				'content' => $report->getContent(),
				'datetimeAdd' => $report->getDatetimeAdd(),
				'memberUsername' => $report->getMemberUsername(),
				'isPast' => $report->isPast(),
				'canDelete' => $caller ? $caller->canDeleteReport($report) : false
			);
		}

		$this->notReportedMembers = array();
		foreach($this->database->findNotReportedMembers($this->projectId, $this->date) as $memberId)
		{
			$this->notReportedMembers[] = (new MemberProxy($memberId, $this->database))->getUsername();
		}
	}

	public function getProjectName()
	{
		return $this->projectName;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function getReports()
	{
		return $this->reports;
	}

	public function getNotReportedMembers()
	{
		return $this->notReportedMembers;
	}
}
