<?

class GroupDAO extends BaseDAO
{
	const CNAME = 'Group';
	
	public static function fetchById($id)
	{
		$ps = new PreparedStatement('SELECT * FROM `group` WHERE id = ?');
		$ps->setValue( 1, $id );
		
		return BaseDAO::createObjectFromFirstRow(self::CNAME, $ps);
	}
	
	public static function fetchByAll()
	{
		$ps = new PreparedStatement('SELECT * FROM `group`');
		
		return BaseDAO::createListFromPreparedStatement(self::CNAME, $ps);
	}

}

?>
