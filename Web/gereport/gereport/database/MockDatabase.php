<?php

namespace gereport\database;

__import('database/Database');

class MockDatabase implements Database
{
	private $members = array
	(
		array('id' => 1, 'username' => 'nghia.buivan', 'password' => '1234567', 'group' => 0),
		array('id' => 2, 'username' => 'canh.nguyenngoc', 'password' => 'toilatoi', 'group' => 1),
		array('id' => 3, 'username' => 'vinh.dangdoduc', 'password' => 'toilatoi', 'group' => 2),
		array('id' => 4, 'username' => 'hoa.dinhquoc', 'password' => 'toilatoi', 'group' => 2)
	);

	private $projects = array
	(
		array('id' => 1, 'name' => 'Asphalt 8'),
		array('id' => 2, 'name' => 'Modern Combat 5'),
		array('id' => 3, 'name' => 'UNO & Friends'),
		array('id' => 4, 'name' => 'Order & Chaos'),
		array('id' => 5, 'name' => 'Dungeon Hunter 5')
	);

	private $membersProjects = array
	(
		array('memberId' => 1, 'projectId' => 1),
		array('memberId' => 2, 'projectId' => 2),
		array('memberId' => 3, 'projectId' => 1),
		array('memberId' => 4, 'projectId' => 1),
	);
	
	private $reports = array(
		array('id' => 1,
			'memberId' => 1,
			'projectId' => 1,
			'dateFor' => '2015-02-11',
			'datetimeAdd' => '2015-02-11 17:30:02',
			'content' => 'Lorem ipsum dolor sit amet, Donec porta.

					Sed porttitor, tellus vitae tincidunt feugiat, sem sapien pellentesque justo, vitae pretium justo risus id nunc. Mauris elit metus, varius sit amet, rhoncus id, malesuada eget, tortor. Aenean eu augue ac nisl tincidunt rutrum. Proin erat justo, pharetra eget, posuere at, malesuada et, nulla.'),
		array('id' => 2,
			'memberId' => 2,
			'projectId' => 1,
			'dateFor' => '2015-02-11',
			'datetimeAdd' => '2015-02-11 18:23:59',
			'content' => 'Lorem ipsum dolor sit amet, Donec porta. Sed porttitor, tellus vitae tincidunt feugiat, sem sapien pellentesque justo, vitae pretium justo risus id nunc.

					Mauris elit metus, varius sit amet, rhoncus id, malesuada eget, tortor. Aenean eu augue ac nisl tincidunt rutrum. Proin erat justo, pharetra eget, posuere at, malesuada et, nulla.')
	);

	private $lastInsertedId = 0;

	//==================================================================================================

	public function isConnected()
	{
		return true;
	}

	public function disconnect()
	{
	}

	public function getLastInsertedId()
	{
		return $this->lastInsertedId;
	}

	//==================================================================================================

	public function hasMember($id)
	{
		foreach ($this->members as $member)
		{
			if ($member['id'] == $id) return true;
		}
		return false;
	}

	public function findMember($id)
	{
		foreach ($this->members as $member)
		{
			if ($member['id'] == $id) return $member;
		}
		return null;
	}

	public function findMemberByLogin($username, $password)
	{
		foreach ($this->members as $member)
		{
			if ($member['username'] == $username && $member['password'] == $password)
			{
				return $member['id'];
			}
		}
		return 0;
	}

	public function findNotReportedMembers($projectId, $date)
	{
		$memberIds = array();
		foreach ($this->membersProjects as $memberProject)
		{
			if ($memberProject['projectId'] == $projectId)
			{
				$memberId = $memberProject['memberId'];
				if (!$this->isMemberReported($memberId, $projectId, $date))
				{
					$memberIds[] = $memberId;
				}
			}
		}
		return $memberIds;
	}

	private function isMemberReported($memberId, $projectId, $date)
	{
		foreach ($this->reports as $report)
		{
			if ($report['projectId'] == $projectId && $report['memberId'] == $memberId
				&& $report['dateFor'] == $date)
			{
				return true;
			}
		}
		return false;
	}

	public function isMemberHasPassword($memberId, $password)
	{
		foreach ($this->members as $member)
		{
			if ($member['id'] == $memberId)
			{
				return $member['password'] == $password;
			}
		}
		return false;
	}

	public function updateMemberPassword($id, $newPassword)
	{
		// TODO: Implement updateMemberPassword() method.
	}

	public function insertMember($username, $password, $group)
	{
		// TODO: Implement insertMember() method.
	}

	//==================================================================================================

	public function isMemberWorkingForProject($memberId, $projectId)
	{
		foreach ($this->membersProjects as $memberProject)
		{
			if ($memberProject['memberId'] == $memberId && $memberProject['projectId'] == $projectId)
			{
				return true;
			}
		}
		return false;
	}

	public function addMemberToProject($memberId, $projectId)
	{

	}

	//==================================================================================================

	public function hasProject($id)
	{
		foreach ($this->projects as $project)
		{
			if ($project['id'] == $id) return true;
		}
		return false;
	}

	public function findProject($id)
	{
		foreach ($this->projects as $project)
		{
			if ($project['id'] == $id) return $project;
		}
		return null;
	}

	public function findProjects()
	{
		$projectIds = array();
		foreach ($this->projects as $project)
		{
			$projectIds[] = $project['id'];
		}
		return $projectIds;
	}

	public function insertProject($name)
	{
		//TODO
	}

	//==================================================================================================

	public function hasReport($id)
	{
		foreach ($this->reports as $report)
		{
			if ($report['id'] == $id) return true;
		}
		return false;
	}

	public function findReport($id)
	{
		foreach ($this->reports as $report)
		{
			if ($report['id'] == $id) return $report;
		}
		return null;
	}

	public function findReportsByProjectAndDate($projectId, $date)
	{
		$reportIds = array();
		foreach ($this->reports as $report)
		{
			if ($report['projectId'] == $projectId && $report['dateFor'] == $date)
			{
				$reportIds[] = $report['id'];
			}
		}
		return $reportIds;
	}

	public function insertReport($memberId, $projectId, $dateFor, $datetimeAdd, $content)
	{
		$id = count($this->reports) + 1;
		$this->reports[] = array
		(
			'id' => $id,
			'memberId' => $memberId,
			'projectId' => $projectId,
			'dateFor' => $dateFor,
			'datetimeAdd' => $datetimeAdd,
			'content' => $content
		);
		$this->lastInsertedId = $id;
	}

	public function deleteReport($reportId)
	{
		foreach ($this->reports as $index => $report)
		{
			if ($report['id'] == $reportId)
			{
				unset( $this->reports[$index] );
				return;
			}
		}
	}
}
