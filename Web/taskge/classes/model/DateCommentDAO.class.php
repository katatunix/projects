<?

class DateCommentDAO extends BaseDAO
{
	const CNAME = 'DateComment';
	
	public static function fetchById($id)
	{
		$ps = new PreparedStatement('SELECT * FROM datecomment WHERE id = ?');
		$ps->setValue( 1, $id );
		
		return BaseDAO::createObjectFromFirstRow(self::CNAME, $ps);
	}
	
	public static function fetchByAll()
	{
		$ps = new PreparedStatement('SELECT * FROM datecomment');
		
		return BaseDAO::createListFromPreparedStatement(self::CNAME, $ps);
	}
	
	public static function fetchByProject($projectId)
	{
		$ps = new PreparedStatement('SELECT * FROM datecomment WHERE projectId = ? ORDER BY `datetime`');
		$ps->setValue(1, $projectId);
		
		return BaseDAO::createListFromPreparedStatement(self::CNAME, $ps);
	}
	
	public static function insert($content, $authorId, $datetime, $projectId)
	{
		$ps = new PreparedStatement(
			'INSERT datecomment(content, authorId, `datetime`, projectId) ' .
			'VALUES(?, ?, ?, ?)'
		);
		$ps->setValue(1, $content);
		$ps->setValue(2, $authorId);
		$ps->setValue(3, $datetime);
		$ps->setValue(4, $projectId);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	public static function delete($id) {
		$ps = new PreparedStatement('DELETE FROM datecomment WHERE id = ?');
		$ps->setValue(1, $id);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}

}

?>
