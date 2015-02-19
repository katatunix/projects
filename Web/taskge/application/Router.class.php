<?

class Router {
	
	private $registry;

	private $path;

	private $args = array();

	public $file;

	public $controller;

	public $action; 

	function __construct($registry) {
		$this->registry = $registry;
	}

	function setPath($path) {
		if ( !is_dir($path) ){
			throw new Exception('Invalid controller path: [' . $path . ']');
		}
		$this->path = $path;
	}
	
	private function makeFile() {
		$this->file = $this->path . '/' . $this->controller . __CONTROLLER_SUFFIX . __CLASS_SUFFIX;
	}

	public function loader()
	{
		$this->makeController();

		if ( !is_readable($this->file) )
		{
			$this->controller = __FILE_NOT_FOUND_CONTROLLER;		
			$this->makeFile();
		}
		
		include $this->file;
		
		$pos = strrpos( $this->controller, '/' );
		if ( $pos ) {
			$this->controller = substr( $this->controller, $pos + 1 );
		}

		$class = $this->controller . __CONTROLLER_SUFFIX;
		$controller = new $class( $this->registry );

		session_start();

		if ( !is_callable(array($controller, $this->action)) ) {
			$action = __DEFAULT_ACTION;
		} else {
			$action = $this->action;
		}

		if ( ! $controller->checkPermission($action) ) {
			$this->controller = __ACCESS_DENIED_CONTROLLER;
			$this->makeFile();
			$class = $this->controller . __CONTROLLER_SUFFIX;
			$controller = new $class( $this->registry );
			$action = __DEFAULT_ACTION;	
		}
		
		$controller->$action();
		
		DataSource::closeCon();
	}

	private function makeController() {
		$route = empty( $_GET[__MY_RT] ) ? '' : $_GET[__MY_RT];

		if ( ! empty($route) ) {
			$pos = strrpos( $route, '/' );
			if ( $pos ) {
				$this->controller = substr( $route, 0, $pos );
				$this->action = substr( $route, $pos + 1 );
			} else {
				$this->controller = $route;
			}
		}

		if ( empty($this->controller) ) {
			$this->controller = __DEFAULT_CONTROLLER;
		}

		if ( empty($this->action) ) {
			$this->action = __DEFAULT_ACTION;
		}
		
		$this->makeFile();
	}

}

?>
