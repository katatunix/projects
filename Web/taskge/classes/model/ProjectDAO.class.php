<?

class ProjectDAO extends BaseDAO
{
	const CNAME = 'Project';
	
	public static function fetchById($id)
	{
		$ps = new PreparedStatement('SELECT * FROM project WHERE id = ?');
		$ps->setValue(1, $id);
		
		return BaseDAO::createObjectFromFirstRow(self::CNAME, $ps);
	}
	
	public static function fetchByAll()
	{
		$ps = new PreparedStatement('SELECT * FROM project ORDER BY name');
		return BaseDAO::createListFromPreparedStatement(self::CNAME, $ps);
	}
	
	public static function fetchByMemberId($memberId)
	{
		$ps = new PreparedStatement(
			'SELECT P.* ' .
			'FROM projectmember AS PM INNER JOIN project AS P ON PM.pid = P.id ' .
			'WHERE PM.mid = ?');
		$ps->setValue( 1, $memberId );
		
		return BaseDAO::createListFromPreparedStatement(self::CNAME, $ps);
	}
	
	public static function deleteMembersList($projectId, $memberIdsList)
	{
		$ps = NULL;
		if ( count($memberIdsList) == 0 )
		{
			$ps = new PreparedStatement('DELETE FROM projectmember WHERE pid = ?');
			$ps->setValue(1, $projectId);
		}
		else
		{
			$ps = new PreparedStatement('DELETE FROM projectmember WHERE pid = ? AND mid IN (?)');
			$ps->setValue( 1, $projectId );
			$ps->setValue( 2, $memberIdsList );
		}
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	public static function insertMember($projectId, $memberId)
	{
		$ps = new PreparedStatement('INSERT projectmember(pid, mid) VALUES(?, ?)');
		$ps->setValue( 1, $projectId );
		$ps->setValue( 2, $memberId );
		return DataSource::instance()->execute( $ps->getSql() );
	}
}

?>
