<?

class MemberDAO extends BaseDAO
{
	const CNAME = 'Member';
	
	public static function fetchById($id)
	{
		$ps = new PreparedStatement('SELECT * FROM member WHERE id = ?');
		$ps->setValue( 1, $id );
		
		return BaseDAO::createObjectFromFirstRow(self::CNAME, $ps);
	}
	
	public static function fetchByLogin($username, $password)
	{
		$ps = new PreparedStatement('SELECT * FROM member WHERE username = ? AND password = ?');
		$ps->setValue( 1, $username );
		$ps->setValue( 2, $password );
		
		return BaseDAO::createObjectFromFirstRow(self::CNAME, $ps);
	}
	
	public static function fetchByAll()
	{
		$ps = new PreparedStatement('SELECT * FROM member ORDER BY username');
		
		return BaseDAO::createListFromPreparedStatement(self::CNAME, $ps);
	}
	
	public static function fetchByProjectId($projectId)
	{
		$ps = new PreparedStatement(
			'SELECT M.* ' .
			'FROM projectmember AS PM INNER JOIN member AS M ON PM.mid = M.id ' .
			'WHERE PM.pid = ? ORDER BY username');
		$ps->setValue( 1, $projectId );
		
		return BaseDAO::createListFromPreparedStatement(self::CNAME, $ps);
	}
	
	public static function fetchByGroupId($groupId)
	{
		$ps = new PreparedStatement('SELECT * FROM member WHERE groupId = ?');
		$ps->setValue( 1, $groupId );
		
		return BaseDAO::createListFromPreparedStatement(self::CNAME, $ps);
	}
	
	public static function updatePassword($id, $newPassword)
	{
		$ps = new PreparedStatement('UPDATE member SET password = ? WHERE id = ?');
		
		$ps->setValue(1, $newPassword);
		$ps->setValue(2, $id);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
}

?>
