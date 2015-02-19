<?php

namespace gereport\domainimpl;

__import('domain/Member');

use gereport\domain\Member;
use gereport\domain\Project;

class MemberImpl implements Member
{
	private $username;
	private $group;

	/**
	 * @var Project[]
	 */
	private $projects;

	public function __construct($username, $group)
	{
		$this->username = $username;
		$this->group = $group;
		$this->projects = array();
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function hasPassword($password)
	{
		return false;
	}

	public function changePassword($newPassword)
	{

	}

	public function isWorkingForProject($projectId)
	{
		return isset($this->projects[$projectId]);
	}

	/**
	 * @param Project $project
	 */
	public function joinProject($project)
	{
		$this->projects[$project->getId()] = $project;
	}

	public function canDeleteReport($report)
	{
		if ($this->group == self::ADMIN) return true;
		return $report->getMemberUsername() == $this->username;
	}
}
