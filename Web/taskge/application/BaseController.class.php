<?

abstract class BaseController {

	protected $registry;

	function __construct($registry) {
		$this->registry = $registry;
	}
	
	public abstract function checkPermission($action);
	
}

?>
