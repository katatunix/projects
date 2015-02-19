<?php

namespace gereport\database;

__import('database/Database');

class MySqlDatabase implements Database
{
	private $link;

	public function __construct($host, $username, $password, $dbname)
	{
		$this->link = new \mysqli($host, $username, $password, $dbname);
		if ($this->link->connect_errno) return;
		$this->link->query("SET NAMES 'utf8'");
	}

	public function isConnected()
	{
		return $this->link->connect_errno ? false : true;
	}

	public function disconnect()
	{
		if ($this->isConnected())
		{
			$this->link->close();
		}
	}

	public function getLastInsertedId()
	{
		return $this->link->insert_id;
	}

	//=====================================================================================================

	public function hasMember($id)
	{
		return $this->hasRow('member', $id);
	}

	private function hasRow($tableName, $id)
	{
		$statement = $this->link->prepare('SELECT `id` FROM `' . $tableName . '` WHERE `id` = ?');
		$statement->bind_param('i', $id);
		return $this->fetchRow($statement) ? true : false;
	}

	/**
	 * @param \mysqli_stmt $statement
	 * @return mixed
	 */
	private function fetchRow($statement)
	{
		$statement->execute();
		$result = $statement->get_result();
		$row = $result->fetch_array();
		$result->free_result();
		$statement->close();
		return $row;
	}

	public function findMember($id)
	{
		return $this->findRow('member', $id);
	}

	public function findRow($tableName, $id)
	{
		$statement = $this->link->prepare('SELECT * FROM `' . $tableName . '` WHERE `id` = ?');
		$statement->bind_param('i', $id);
		return $this->fetchRow($statement);
	}

	public function findMemberByLogin($username, $password)
	{
		$statement = $this->link->prepare('SELECT `id` FROM `member` WHERE `username` = ? AND `password` = ?');
		$statement->bind_param('ss', $username, $password);
		$row = $this->fetchRow($statement);
		return $row ? $row['id'] : 0;
	}

	public function findNotReportedMembers($projectId, $date)
	{
		$statement = $this->link->prepare('
			SELECT M.`id`
			FROM `member` M, `memberproject` MP
			WHERE
				M.`id` = MP.`memberId` AND
				MP.`projectId` = ? AND
				M.`id` NOT IN (
					SELECT A.`memberId` FROM `report` A
					WHERE A.`projectId` = ?
						AND A.`dateFor` = ?
				)
			ORDER BY M.`username`
		');
		$statement->bind_param('iis', $projectId, $projectId, $date);
		return $this->fetchIdsFromStatement($statement);
	}

	/**
	 * @param \mysqli_stmt $statement
	 * @return array
	 */
	private function fetchIdsFromStatement($statement)
	{
		$statement->execute();
		$result = $statement->get_result();
		$ids = $this->fetchIdsFromResult($result);
		$statement->close();
		return $ids;
	}

	/**
	 * @param \mysqli_result $result
	 * @return array
	 */
	private function fetchIdsFromResult($result)
	{
		$ids = array();
		while ($row = $result->fetch_array())
		{
			$ids[] = $row['id'];
		}
		$result->free_result();
		return $ids;
	}

	public function isMemberHasPassword($memberId, $password)
	{
		$statement = $this->link->prepare('SELECT `id` FROM `member` WHERE `id` = ? AND `password` = ?');
		$statement->bind_param('is', $memberId, $password);
		return $this->fetchRow($statement) ? true : false;
	}

	public function updateMemberPassword($id, $newPassword)
	{
		$statement = $this->link->prepare('UPDATE `member` SET `password` = ? WHERE `id` = ?');
		$statement->bind_param('si', $newPassword, $id);
		$ok = $statement->execute();
		$statement->close();
		if (!$ok) throw new \Exception('updateMemberPassword: database error');
	}

	public function insertMember($username, $password, $group)
	{
		$statement = $this->link->prepare(
			'INSERT INTO `member`(`username`, `password`, `group`) VALUES(?, ?, ?)');
		$statement->bind_param('ssi', $username, $password, $group);
		$ok = $statement->execute();
		$statement->close();
		if (!$ok) throw new \Exception('insertMember: database error');
	}

	//=====================================================================================================

	public function hasProject($id)
	{
		return $this->hasRow('project', $id);
	}

	public function findProject($id)
	{
		return $this->findRow('project', $id);
	}

	public function findProjects()
	{
		return $this->findRowIds('project', 'name');
	}

	private function findRowIds($tableName, $orderBy = 'id')
	{
		$result = $this->link->query('SELECT `id` FROM `' . $tableName . '` ORDER BY `' . $orderBy . '`');
		return $this->fetchIdsFromResult($result);
	}

	public function insertProject($name)
	{
		$statement = $this->link->prepare('INSERT INTO `project`(`name`) VALUES(?)');
		$statement->bind_param('s', $name);
		$ok = $statement->execute();
		$statement->close();
		if (!$ok) throw new \Exception('insertProject: database error');
	}

	//=====================================================================================================

	public function isMemberWorkingForProject($memberId, $projectId)
	{
		$statement = $this->link->prepare('
			SELECT `memberId` FROM `memberproject` WHERE `memberId` = ? AND `projectId` = ?
		');
		$statement->bind_param('ii', $memberId, $projectId);
		return $this->fetchRow($statement) ? true : false;
	}

	public function addMemberToProject($memberId, $projectId)
	{
		$statement = $this->link->prepare('INSERT INTO `memberproject`(`memberId`, `projectId`) VALUES(?, ?)');
		$statement->bind_param('ii', $memberId, $projectId);
		$ok = $statement->execute();
		$statement->close();
		if (!$ok) throw new \Exception('addMemberToProject: database error');
	}

	//=====================================================================================================

	public function hasReport($id)
	{
		return $this->hasRow('report', $id);
	}

	public function findReport($id)
	{
		return $this->findRow('report', $id);
	}

	public function findReportsByProjectAndDate($projectId, $date)
	{
		$statement = $this->link->prepare('
			SELECT `id` FROM `report` WHERE `projectId` = ? AND `dateFor` = ? ORDER BY `datetimeAdd` DESC
		');
		$statement->bind_param('is', $projectId, $date);
		return $this->fetchIdsFromStatement($statement);
	}

	public function insertReport($memberId, $projectId, $dateFor, $datetimeAdd, $content)
	{
		$statement = $this->link->prepare('
			INSERT INTO `report`(`memberId`, `projectId`, `dateFor`, `datetimeAdd`, `content`)
			VALUES(?, ?, ?, ?, ?)
		');
		$statement->bind_param('iisss', $memberId, $projectId, $dateFor, $datetimeAdd, $content);
		$ok = $statement->execute();
		$statement->close();
		if (!$ok) throw new \Exception('insertReport: database error');
	}

	public function deleteReport($reportId)
	{
		$statement = $this->link->prepare('DELETE FROM `report` WHERE `id` = ?');
		$statement->bind_param('i', $reportId);
		$ok = $statement->execute();
		$statement->close();
		if (!$ok) throw new \Exception('deleteReport: database error');
	}
}
