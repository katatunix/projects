<?php

namespace gereport\domainproxy;

__import('domain/Member');
__import('domainimpl/MemberImpl');
__import('domainproxy/Proxy');

use gereport\domain\Member;
use gereport\domain\Project;
use gereport\domain\Report;
use gereport\domainimpl\MemberImpl;

class MemberProxy extends Proxy implements Member
{
	public function __construct($id, $database)
	{
		parent::__construct($id, $database);
	}

	public function getUsername()
	{
		return $this->createMemberImpl()->getUsername();
	}

	public function hasPassword($password)
	{
		return $this->database->isMemberHasPassword($this->id, $password);
	}

	public function changePassword($newPassword)
	{
		$this->database->updateMemberPassword($this->id, $newPassword);
	}

	public function isWorkingForProject($projectId)
	{
		return $this->database->isMemberWorkingForProject($this->id, $projectId);
	}

	/**
	 * @param Project $project
	 */
	public function joinProject($project)
	{
		// TODO: Implement joinProject() method.
		// Database
	}

	/**
	 * @param Report $report
	 * @return bool
	 */
	public function canDeleteReport($report)
	{
		return $this->createMemberImpl()->canDeleteReport($report);
	}

	private function createMemberImpl()
	{
		$data = $this->database->findMember($this->id);
		return new MemberImpl($data['username'], $data['group']);
	}
}
