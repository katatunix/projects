<?

class TaskBean extends BaseBean
{
	public function __construct($id)
	{
		parent::__construct($id);
	}
	
	//
	const MSG_TITLE = 'Title can not be blank';
	
	//
	private $title = NULL;
	public function setTitle($value) { $this->title = $value; }
	public function title() { return $this->title; }
	public static function checkValidTitle($value)
	{
		return isContentString($value);
	}
	
	//
	private $desc = NULL;
	public function setDesc($value) { $this->desc = $value; }
	public function desc() { return $this->desc; }
	public static function checkValidDesc($value)
	{
		return is_null($value) || is_string($value);
	}
	
	//
	private $priority = NULL;
	public function setPriority($value) { $this->priority = $value; }
	public function priority() { return $this->priority; }
	public static function checkValidPriority($value)
	{
		return isPositiveIntInStringFormat($value) && ( (int)$value <= 5 );
	}
	
	//
	private $authorId = NULL;
	public function setAuthorId($value) { $this->authorId = $value; }
	public function authorId() { return $this->authorId; }
	public static function checkValidAuthorId($value)
	{
		return isPositiveIntInStringFormat($value);
	}
	public function author()
	{
		if ( ! self::checkValidAuthorId($this->authorId) ) return NULL;
		return BaseDAO::findInCache( MemberDAO::CNAME, $this->authorId, TRUE );
	}
	
	//
	private $assigneeId = NULL;
	public function setAssigneeId($value) { $this->assigneeId = $value; }
	public function assigneeId() { return $this->assigneeId; }
	public static function checkValidAssigneeId($value)
	{
		return is_null($value) || isPositiveIntInStringFormat($value);
	}
	public function assignee()
	{
		if ( ! self::checkValidAssigneeId($this->assigneeId) ) return NULL;
		return BaseDAO::findInCache( MemberDAO::CNAME, $this->assigneeId, TRUE );
	}
	
	//
	private $createdDate = NULL;
	public function setCreatedDate($value) { $this->createdDate = $value; }
	public function createdDate() { return $this->createdDate; }
	public static function checkValidCreatedDate($value)
	{
		// todo: check date format
		return isContentString($value);
	}
	
	//
	private $percentComplete = NULL;
	public function setPercentComplete($value) { $this->percentComplete = $value; }
	public function percentComplete() { return $this->percentComplete; }
	public static function checkValidPercentComplete($value)
	{
		return isPositiveIntInStringFormat($value) && ( (int)$value <= 100 );
	}
	
	//
	private $lastUpdaterId = NULL;
	public function setLastUpdaterId($value) { $this->lastUpdaterId = $value; }
	public function lastUpdaterId() { return $this->lastUpdaterId; }
	public static function checkValidLastUpdaterId($value)
	{
		return isPositiveIntInStringFormat($value);
	}
	public function lastUpdater()
	{
		if ( ! self::checkValidLastUpdaterId($this->lastUpdaterId) ) return NULL;
		return BaseDAO::findInCache( MemberDAO::CNAME, $this->lastUpdaterId, TRUE );
	}
	
	//
	private $lastUpdatedDatetime = NULL;
	public function setLastUpdatedDatetime($value) { $this->lastUpdatedDatetime = $value; }
	public function lastUpdatedDatetime() { return $this->lastUpdatedDatetime; }
	public static function checkValidLastUpdatedDatetime($value)
	{
		// todo: check datetime format
		return isContentString($value);
	}
	
	//
	private $projectId = NULL;
	public function setProjectId($value) { $this->projectId = $value; }
	public function projectId() { return $this->projectId; }
	public static function checkValidProjectId($value)
	{
		return isPositiveIntInStringFormat($value);
	}
	public function project()
	{
		if ( ! self::checkValidProjectId($this->projectId) ) return NULL;
		return BaseDAO::findInCache( ProjectDAO::CNAME, $this->projectId, TRUE );
	}
	
	//
	private $commentsList = NULL;
	public function commentsList()
	{
		if ( is_null($this->commentsList) )
		{
			$this->commentsList = TaskCommentDAO::fetchByTaskId($this->id);
		}
		return $this->commentsList;
	}
	
	//
	public function loadDataFromArray($arr)
	{
		$this->setTitle					(	$arr['title']				);
		$this->setDesc					(	$arr['desc']				);
		$this->setPriority				(	$arr['priority']			);
		$this->setAuthorId				(	$arr['authorId']			);
		$this->setAssigneeId			(	$arr['assigneeId']			);
		$this->setCreatedDate			(	$arr['createdDate']			);
		$this->setPercentComplete		(	$arr['percentComplete']		);
		$this->setLastUpdaterId			(	$arr['lastUpdaterId']		);
		$this->setLastUpdatedDatetime	(	$arr['lastUpdatedDatetime']	);
		$this->setProjectId				(	$arr['projectId']			);
	}
	
	public function toArray($isEscapeHtml = FALSE)
	{
		return array(
			'id'					=> $this->id,
			'title'					=> $isEscapeHtml ? htmlspecialchars($this->title) : $this->title,
			'desc'					=> $isEscapeHtml ? htmlspecialchars($this->desc) : $this->desc,
			'priority'				=> $this->priority,
			'authorId'				=> $this->authorId,
			'assigneeId'			=> $this->assigneeId,
			'createdDate'			=> $this->createdDate,
			'percentComplete'		=> $this->percentComplete,
			'lastUpdaterId'			=> $this->lastUpdaterId,
			'lastUpdatedDatetime'	=> $this->lastUpdatedDatetime,
			'projectId'				=> $this->projectId
		);
	}
	
	public function escapeHtml()
	{
		$this->title = htmlspecialchars($this->title);
		$this->desc = htmlspecialchars($this->desc);
	}
}

?>
