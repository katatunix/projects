<?php

namespace gereport\domainimpl;

__import('domain/Report');

use gereport\domain\Member;
use gereport\domain\Project;
use gereport\domain\Report;

class ReportImpl implements Report
{
	/**
	 * @var Member
	 */
	private $member;

	/**
	 * @var Project
	 */
	private $project;

	private $dateFor;
	private $datetimeAdd;
	private $content;

	public function __construct($member, $project, $dateFor, $datetimeAdd, $content)
	{
		$this->member = $member;
		$this->project = $project;
		$this->dateFor = $dateFor;
		$this->datetimeAdd = $datetimeAdd;
		$this->content = $content;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function getDatetimeAdd()
	{
		return $this->datetimeAdd;
	}

	public function getMemberUsername()
	{
		return $this->member->getUsername();
	}

	public function isPast()
	{
		return !$this->member->isWorkingForProject($this->project->getId());
	}
}
