<?

class TaskCommentBean extends BaseBean
{
	public function __construct($id)
	{
		parent::__construct($id);
	}
	
	//
	const MSG_CONTENT = 'Content can not be blank';
	
	//
	private $content = NULL;
	public function setContent($value) { $this->content = $value; }
	public function content() { return $this->content; }
	public static function checkValidContent($value)
	{
		return isContentString($value);
	}
	
	//
	private $taskId = NULL;
	public function setTaskId($value) { $this->taskrId = $value; }
	public function taskId() { return $this->taskId; }
	public static function checkValidTaskId($value)
	{
		return isPositiveIntInStringFormat($value);
	}
	public function task()
	{
		if ( ! self::checkValidTaskId($this->taskId) ) return NULL;
		return BaseDAO::findInCache( TaskDAO::CNAME, $this->taskId, TRUE );
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
	private $datetime = NULL;
	public function setDatetime($value) { $this->datetime = $value; }
	public function datetime() { return $this->datetime; }
	public static function checkValidDatetime($value)
	{
		return isContentString($value);
	}
	
	//
	public function loadDataFromArray($arr)
	{
		$this->setContent	(	$arr['content']		);
		$this->setTaskId	(	$arr['taskId']		);
		$this->setAuthorId	(	$arr['authorId']	);
		$this->setDatetime	(	$arr['datetime']	);
	}
	
	public function toArray($isEscapeHtml = FALSE)
	{
		return array(
			'id'			=> $this->id,
			'content'		=> $isEscapeHtml ? htmlspecialchars($this->content) : $this->content,
			'taskId'		=> $this->taskId,
			'authorId'		=> $this->authorId,
			'datetime'		=> $this->datetime
		);
	}
	
	public function escapeHtml()
	{
		$this->content = htmlspecialchars($this->content);
	}
}

?>
