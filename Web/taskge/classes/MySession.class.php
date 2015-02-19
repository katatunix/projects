<?

class MySession
{
	public static function add($key, $value)
	{
		$_SESSION[$key] = $value;
	}
	
	public static function remove($key)
	{
		if ( isset($_SESSION[$key]) ) unset( $_SESSION[$key] );
	}
	
	public static function get($key)
	{
		if ( isset($_SESSION[$key]) ) return $_SESSION[$key];
		return NULL;
	}
	
	//==========================================================================================
	
	const CURRENT_MEMBER_ID_SESSION_KEY = 'TaskPub_CurrentMemberId';
	
	private static $currentMember = NULL;
	
	public static function deleteCurrentMember()
	{
		MySession::remove( self::CURRENT_MEMBER_ID_SESSION_KEY );
		self::$currentMember = NULL;
	}
	
	public static function currentMember()
	{
		if ( is_null(self::$currentMember) )
		{
			$id = MySession::get( self::CURRENT_MEMBER_ID_SESSION_KEY );
			if ( is_null($id) ) return NULL;
			self::$currentMember = BaseDAO::findInCache(MemberDAO::CNAME, $id, TRUE);
		}
		
		return self::$currentMember;
	}
	
	public static function setCurrentMember($obj)
	{
		MySession::add( self::CURRENT_MEMBER_ID_SESSION_KEY, $obj->id() );
		self::$currentMember = $obj;
	}
}

?>
