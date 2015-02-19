<?

class GroupBean extends BaseBean
{
	public function __construct($id)
	{
		parent::__construct($id);
	}
	
	//
	const MSG_NAME = 'Name can not be blank';
	
	//
	private $name = NULL;
	public function setName($value) { $this->name = $value; }
	public function name() { return $this->name; }
	public static function checkValidName($value)
	{
		return isContentString($value);
	}
	
	//
	private $membersList = NULL;
	public function membersList()
	{
		if ( is_null($this->membersList) )
		{
			$this->membersList = MemberDAO::fetchByGroupId($this->id);
		}
		return $this->membersList;
	}
	
	//
	public function loadDataFromArray($arr)
	{
		$this->setName	(	$arr['name']	);
	}
	
	public function toArray($isEscapeHtml = FALSE)
	{
		return array(
			'id'			=> $this->id,
			'name'			=> $isEscapeHtml ? htmlspecialchars($this->name) : $this->name
		);
	}
	
	public function escapeHtml()
	{
		$this->name = htmlspecialchars($this->name);
	}
}

?>
