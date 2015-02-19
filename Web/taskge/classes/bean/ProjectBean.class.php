<?

__include_file('utils.php');

class ProjectBean extends BaseBean
{
	public function __construct($id)
	{
		parent::__construct($id);
	}
	
	//
	const MSG_NAME				= 'Name can not be blank';
	const MSG_NAME_DUPLICATED	= 'Name can not be duplicated';
	
	//
	private $name = NULL;
	public function setName($value) { $this->name = $value; }
	public function name() { return $this->name; }
	public static function checkValidName($value)
	{
		return isContentString($value);
	}
	
	//
	private $producerId = NULL;
	public function setProducerId($value) { $this->producerId = $value; }
	public function producerId() { return $this->producerId; }
	public static function checkValidProducerId($value)
	{
		return is_null($value) || isPositiveIntInStringFormat($value);
	}
	public function producer()
	{
		if ( ! self::checkValidProducerId($this->producerId) ) return NULL;
		return BaseDAO::findInCache( MemberDAO::CNAME, $this->producerId, TRUE );
	}
	
	//
	private $leadDevId = NULL;
	public function setLeadDevId($value) { $this->leadDevId = $value; }
	public function leadDevId() { return $this->leadDevId; }
	public static function checkValidLeadDevId($value)
	{
		return is_null($value) || isPositiveIntInStringFormat($value);
	}
	public function leadDev()
	{
		if ( ! self::checkValidLeadDevId($this->leadDevId) ) return NULL;
		return BaseDAO::findInCache( MemberDAO::CNAME, $this->leadDevId, TRUE );
	}
	
	//
	private $leadQAId = NULL;
	public function setLeadQAId($value) { $this->leadQAId = $value; }
	public function leadQAId() { return $this->leadQAId; }
	public static function checkValidLeadQAId($value)
	{
		return is_null($value) || isPositiveIntInStringFormat($value);
	}
	public function leadQA()
	{
		if ( ! self::checkValidLeadQAId($this->leadQAId) ) return NULL;
		return BaseDAO::findInCache( MemberDAO::CNAME, $this->leadQAId, TRUE );
	}
	
	//
	private $membersList = NULL;
	public function membersList()
	{
		if ( is_null($this->membersList) )
		{
			$this->membersList = MemberDAO::fetchByProjectId($this->id);
		}
		return $this->membersList;
	}
	
	//
	public function loadDataFromArray($arr)
	{
		$this->setName			(	$arr['name']		);
		$this->setLeadDevId		(	$arr['leadDevId']	);
		$this->setLeadQAId		(	$arr['leadQAId']	);
		$this->setProducerId	(	$arr['producerId']	);
	}
	
	public function toArray($isEscapeHtml = FALSE)
	{
		return array(
			'id'			=> $this->id,
			'name'			=> $isEscapeHtml ? htmlspecialchars($this->name) : $this->name,
			'leadDevId'		=> $this->leadDevId,
			'leadQAId'		=> $this->leadQAId,
			'producerId'	=> $this->producerId
		);
	}
	
	public function escapeHtml()
	{
		$this->name = htmlspecialchars($this->name);
	}
}

?>
