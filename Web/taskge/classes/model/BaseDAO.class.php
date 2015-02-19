<?

abstract class BaseDAO
{
	private static $cache = array();
	
	private static function createObject($className, $id, $fetch)
	{
		switch ($className)
		{
			case DateCommentDAO::CNAME:		return $fetch ? DateCommentDAO::fetchById( $id )	: new DateCommentBean( $id );	break;
			case GroupDAO::CNAME:			return $fetch ? GroupDAO::fetchById( $id )			: new GroupBean( $id );			break;
			case MemberDAO::CNAME:			return $fetch ? MemberDAO::fetchById( $id )			: new MemberBean( $id );		break;
			case ProjectDAO::CNAME:			return $fetch ? ProjectDAO::fetchById( $id )		: new ProjectBean( $id );		break;
			case TaskDAO::CNAME:			return $fetch ? TaskDAO::fetchById( $id )			: new TaskBean( $id );			break;
			case TaskCommentDAO::CNAME:		return $fetch ? TaskCommentDAO::fetchById( $id )	: new TaskCommentBean( $id );	break;
		}
		return NULL;
	}
	
	public static function findInCache($className, $id, $fetch = FALSE)
	{
		if ( is_null($id) ) return NULL;
		
		$beanClassName = $className . __BEAN_SUFFIX;
		
		if ( isset(self::$cache[$beanClassName]) )
		{
			if ( isset(self::$cache[$beanClassName][$id]) )
			{
				return self::$cache[$beanClassName][$id];
			}
		}
		
		$obj = self::createObject($className, $id, $fetch);
		
		if ( $obj )
		{
			if ( ! isset(self::$cache[$beanClassName]) )
			{
				self::$cache[$beanClassName] = array();
			}
			self::$cache[$beanClassName][$id] = $obj;
		}
		
		return $obj;
	}
	
	public static function createListFromPreparedStatement($className, $ps)
	{
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$list = array();
		
		while ( $row = mysql_fetch_array($rs) )
		{
			$id = $row['id'];
			$obj = self::findInCache($className, $id);
			$obj->loadDataFromArray($row);
			
			$list[] = $obj;
		}
		
		mysql_free_result($rs);
		
		return $list;
	}
	
	public static function createObjectFromFirstRow($className, $ps)
	{
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$obj = NULL;
		
		if ( $row = mysql_fetch_array($rs) )
		{
			$id = $row['id'];
			$obj = self::findInCache($className, $id);
			$obj->loadDataFromArray($row);
		}
		
		mysql_free_result($rs);
		
		return $obj;
	}
	
}

?>
