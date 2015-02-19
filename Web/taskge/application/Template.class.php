<?

class Template {

	private $registry;
	
	private $vars = array();

	function __construct($registry) {
		$this->registry = $registry;
	}

	public function __set($index, $value) {
		$this->vars[$index] = $value;
	}

	function show($layout) {
		$path = __SITE_PATH . '/' . __VIEW_DIR . '/' . $layout;

		if ( !file_exists($path) ) {
			throw new Exception('Template not found in '. $path);
			return false;
		}
		
		foreach ($this->vars as $key => $value) {
			$$key = $value;
		}

		include $path;
	}

}

?>
