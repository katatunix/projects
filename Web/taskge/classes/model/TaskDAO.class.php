<?

class TaskDAO extends BaseDAO
{
	const CNAME = 'Task';
	
	public static function fetchById($id)
	{
		$ps = new PreparedStatement('SELECT * FROM task WHERE id = ?');
		$ps->setValue( 1, $id );
		
		return BaseDAO::createObjectFromFirstRow(self::CNAME, $ps);
	}
	
	public static function fetchByDailyAssignment($projectId, $filterDate, $memberIdsList)
	{
		$sql = 'SELECT * FROM task WHERE projectId = ?';
		
		if ( $filterDate )
		{
			$sql .= ' AND createdDate <= ? AND (percentComplete < 100 OR ? <= lastUpdatedDatetime)';
		}
		
		if ( $memberIdsList && count($memberIdsList) > 0 )
		{
			$sql .= ' AND assigneeId IN (?) ORDER BY createdDate';
		}
		
		$ps = new PreparedStatement($sql);
		$index = 1;
		
		$ps->setValue($index, $projectId);
		$index++;
		
		if ( $filterDate )
		{
			$ps->setValue($index, $filterDate . ' 23:59:59');
			$index++;
			
			$ps->setValue($index, $filterDate);
			$index++;
		}
		
		if ( count($memberIdsList) > 0 )
		{
			$ps->setValue($index, $memberIdsList);
			$index++;
		}
		
		return BaseDAO::createListFromPreparedStatement(self::CNAME, $ps);
	}
	
	public static function insert(	$title, $desc, $priority, $authorId, $assigneeId, $createdDate,
									$percentComplete, $lastUpdaterId, $lastUpdatedDatetime, $projectId)
	{
		$ps = new PreparedStatement(
			'INSERT task(title,`desc`,priority,authorId,assigneeId,createdDate,percentComplete,lastUpdaterId,lastUpdatedDatetime,projectId) ' .
			'VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
		$ps->setValue(1, $title);
		$ps->setValue(2, $desc);
		$ps->setValue(3, $priority);
		$ps->setValue(4, $authorId);
		$ps->setValue(5, $assigneeId);
		$ps->setValue(6, $createdDate);
		$ps->setValue(7, $percentComplete);
		$ps->setValue(8, $lastUpdaterId);
		$ps->setValue(9, $lastUpdatedDatetime);
		$ps->setValue(10, $projectId);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	public static function update($id, $title, $desc, $priority, $assigneeId)
	{
		$ps = new PreparedStatement('UPDATE task SET title = ?, `desc` = ?, priority = ?, assigneeId = ? WHERE id = ?');
		$ps->setValue(1, $title);
		$ps->setValue(2, $desc);
		$ps->setValue(3, $priority);
		$ps->setValue(4, $assigneeId);
		$ps->setValue(5, $id);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	public static function updatePercentComplete($id, $percentComplete, $lastUpdaterId, $lastUpdatedDatetime)
	{
		$ps = new PreparedStatement('UPDATE task SET percentComplete=?, lastUpdaterId=?, lastUpdatedDatetime=? WHERE id = ?');
		$ps->setValue(1, $percentComplete);
		$ps->setValue(2, $lastUpdaterId);
		$ps->setValue(3, $lastUpdatedDatetime);
		$ps->setValue(4, $id);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	public static function delete($id)
	{
		$ps = new PreparedStatement('DELETE FROM task WHERE id = ?');
		$ps->setValue(1, $id);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
}

?>
