<?php

namespace gereport\domainproxy;

__import('domain/Report');
__import('domainimpl/ReportImpl');

__import('domainproxy/Proxy');
__import('domainproxy/MemberProxy');
__import('domainproxy/ProjectProxy');

use gereport\domain\Report;
use gereport\domainimpl\ReportImpl;

class ReportProxy extends Proxy implements Report
{
	public function __construct($id, $database)
	{
		parent::__construct($id, $database);
	}

	public function getContent()
	{
		return $this->createReportImpl()->getContent();
	}

	public function getDatetimeAdd()
	{
		return $this->createReportImpl()->getDatetimeAdd();
	}

	public function getMemberUsername()
	{
		return $this->createReportImpl()->getMemberUsername();
	}

	public function isPast()
	{
		return $this->createReportImpl()->isPast();
	}

	private function createReportImpl()
	{
		$data = $this->database->findReport($this->id);
		return new ReportImpl(
			new MemberProxy($data['memberId'], $this->database),
			new ProjectProxy($data['projectId'], $this->database),
			$data['dateFor'],
			$data['datetimeAdd'],
			$data['content']);
	}
}
