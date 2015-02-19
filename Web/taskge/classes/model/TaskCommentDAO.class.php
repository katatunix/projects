<?

class TaskCommentDAO extends BaseDAO
{
	const CNAME = 'TaskComment';
	
	public static function fetchById($id)
	{
		$ps = new PreparedStatement('SELECT * FROM taskcomment WHERE id = ?');
		$ps->setValue( 1, $id );
		
		return BaseDAO::createObjectFromFirstRow(self::CNAME, $ps);
	}
	
	public static function fetchByAll()
	{
		$ps = new PreparedStatement('SELECT * FROM taskcomment');
		
		return BaseDAO::createListFromPreparedStatement(self::CNAME, $ps);
	}
	
	public static function fetchByTaskId($taskId)
	{
		$ps = new PreparedStatement('SELECT * FROM taskcomment WHERE taskId = ? ORDER BY `datetime`');
		$ps->setValue(1, $taskId);
		
		return BaseDAO::createListFromPreparedStatement(self::CNAME, $ps);
	}
	
	public static function insert($content, $taskId, $authorId, $datetime)
	{
		$ps = new PreparedStatement(
			'INSERT taskcomment(content, taskId, authorId, `datetime`) ' .
			'VALUES(?, ?, ?, ?)'
		);
		$ps->setValue(1, $content);
		$ps->setValue(2, $taskId);
		$ps->setValue(3, $authorId);
		$ps->setValue(4, $datetime);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	public static function delete($id)
	{
		$ps = new PreparedStatement('DELETE FROM taskcomment WHERE id = ?');
		$ps->setValue(1, $id);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}

}

?>
