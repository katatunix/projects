<?

__autoload('DataSource');

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

	public function loader()
	{
		$this->getController();

		if ( !is_readable($this->file) )
		{			
			$this->file = $this->path . '/error404.php';
			$this->controller = 'error404';
		}
		
		include $this->file;
		
		$pos = strrpos( $this->controller, '/' );
		if ( $pos ) {
			$this->controller = substr( $this->controller, $pos + 1 );
		}

		$class = $this->controller . 'Controller';
		$controller = new $class( $this->registry );

		session_start();

		if ( !is_callable(array($controller, $this->action)) ) {
			$action = 'index';
		} else {
			$action = $this->action;
		}
		
		$controller->countStats();

		if ( $controller->checkPermission($action) ) {
			$controller->$action();
		} else {
			$controller->showError777();
		}
		
		DataSource::closeCon();
	}

	private function getController() {
		$route = empty( $_GET[__MY_RT] ) ? '' : $_GET[__MY_RT];

		if ( empty($route) ) {
			$route = 'index';
		} else {
			$pos = strrpos( $route, '/' );
			if ( $pos ) {
				$this->controller = substr( $route, 0, $pos );
				$this->action = substr( $route, $pos + 1 );
			} else {
				$this->controller = $route;
			}
		}

		if ( empty($this->controller) ) {
			$this->controller = 'index';
		}

		if ( empty($this->action) ) {
			$this->action = 'index';
		}
		
		$this->file = $this->path . '/'. $this->controller . '.php';
	}

}

?>
