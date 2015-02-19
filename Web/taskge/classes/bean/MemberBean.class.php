<?

class MemberBean extends BaseBean
{
	public function __construct($id)
	{
		parent::__construct($id);
	}
	
	//
	const MSG_USERNAME				= 'Username can not be blank';
	const MSG_PASSWORD				= 'Password can not be blank';
	const MSG_PASSWORD_NOT_MATCHED	= 'The 2 Passwords are not matched';
	const MSG_GROUP_ID				= 'GroupId can not be blank and must be a positive integer';
	const MSG_GROUP_ID_NOT_FOUND	= 'The GroupId is not found in database';
	const MSG_MEMBER_NOT_FOUND		= 'The Username and the Password are not found in database';
	const MSG_WRONG_CURRENT_PASSWORD = 'Wrong current password';
	
	//
	private $username = NULL;
	public function setUsername($value) { $this->username = $value; }
	public function username() { return $this->username; }
	public static function checkValidUsername($value)
	{
		return isContentString($value);
	}
	
	//
	private $password = NULL;
	public function setPassword($value) { $this->password = $value; }
	public function password() { return $this->password; }
	public static function checkValidPassword($value)
	{
		return isContentString($value);
	}
	
	//
	private $groupId = NULL;
	public function setGroupId($value) { $this->groupId = $value; }
	public function groupId() { return $this->groupId; }
	public static function checkValidGroupId($value)
	{
		return isPositiveIntInStringFormat($value);
	}
	public function group()
	{
		if ( ! self::checkValidGroupId($this->groupId) ) return NULL;
		return BaseDAO::findInCache( GroupDAO::CNAME, $this->groupId, TRUE );
	}
	
	//
	private $projectsList = NULL;
	public function projectsList()
	{
		if ( is_null($this->projectsList) )
		{
			$this->projectsList = ProjectDAO::fetchByMemberId($this->id);
		}
		return $this->projectsList;
	}
	
	//
	public function loadDataFromArray($arr)
	{
		$this->setUsername	(	$arr['username']	);
		$this->setPassword	(	$arr['password']	);
		$this->setGroupId	(	$arr['groupId']		);
	}
	
	public function toArray($isEscapeHtml = FALSE)
	{
		return array(
			'id'			=> $this->id,
			'username'		=> $isEscapeHtml ? htmlspecialchars($this->username) : $this->username,
			'password'		=> $this->password,
		);
	}
	
	public function escapeHtml()
	{
		$this->username = htmlspecialchars($this->username);
	}
}

?>
