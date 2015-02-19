<?

class DateCommentBean extends BaseBean
{
	public function __construct($id)
	{
		parent::__construct($id);
	}
	
	//
	const MSG_CONTENT = 'Content of the comment can not be blank';
	
	//
	private $content = NULL;
	public function setContent($value) { $this->content = $value; }
	public function content() { return $this->content; }
	public static function checkValidContent($value)
	{
		return isContentString($value);
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
	public function loadDataFromArray($arr)
	{
		$this->setContent	(	$arr['content']		);
		$this->setAuthorId	(	$arr['authorId']	);
		$this->setDatetime	(	$arr['datetime']	);
		$this->setProjectId	(	$arr['projectId']	);
	}
	
	public function toArray($isEscapeHtml = FALSE)
	{
		return array(
			'id'			=> $this->id,
			'content'		=> $isEscapeHtml ? htmlspecialchars($this->content) : $this->content,
			'authorId'		=> $this->authorId,
			'datetime'		=> $this->datetime,
			'projectId'		=> $this->projectId
		);
	}
	
	public function escapeHtml()
	{
		$this->content = htmlspecialchars($this->content);
	}
}

?>
