<?

abstract class BaseBean
{
	protected $id;
	
	public function __construct($id)
	{
		$this->id = $id;
	}
	
	public function id()
	{
		return $this->id;
	}
	
	public abstract function loadDataFromArray($arr);
	
	public abstract function toArray($isEscapeHtml = FALSE);
	
	public abstract function escapeHtml();
}

?>
