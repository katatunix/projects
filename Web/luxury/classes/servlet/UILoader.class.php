<?php

class UILoader {
	
	private $vars = array();

	function __construct() {
	}

	public function __set($index, $value) {
		$this->vars[$index] = $value;
	}

	public function show($layout) {
		$path = __UI_DIR_PATH . '/' . $layout;

		if ( !file_exists($path) ) {
			throw new Exception('Layout not found in '. $path);
			return;
		}
		
		foreach ($this->vars as $key => $value) {
			$$key = $value;
		}

		include $path;
	}

}

?>
