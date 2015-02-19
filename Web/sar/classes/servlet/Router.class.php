<?php

__import('miscutils/MiscUtils');

class Router {

	private $path;
	private $file;
	private $servlet;
	private $servletName;

	public function setServletDirPath($path) {
		if ( !is_dir($path) ){
			throw new Exception('Invalid servlet path: [' . $path . ']');
		}
		$this->path = $path;
	}

	public function executeServlet() {
		$this->makeServlet();

		if ( !is_readable($this->file) )
		{
			$this->servlet = __PAGE_NOT_FOUND_SERVLET;
			$this->servletName = $this->servlet;
			$this->makeFile();
		}
		
		require_once $this->file;

		$class = $this->servletName . __SERVLET_SUFFIX;
		$servlet = new $class();

		session_start();

		if ( !is_callable(array($servlet, $this->action)) ) {
			$action = __DEFAULT_ACTION;
		} else {
			$action = $this->action;
		}

		if ( ! $servlet->checkPermission($action) ) {
			$this->servlet = __ACCESS_DENIED_SERVLET;
			$this->servletName = $this->servlet;
			$this->makeFile();
			$class = $this->servletName . __SERVLET_SUFFIX;
			require_once $this->file;
			$servlet = new $class();
			$action = __DEFAULT_ACTION;	
		}
		
		$servlet->$action();
	}

	private function makeServlet() {
		$route = empty( $_GET[__MY_RT] ) ? '' : $_GET[__MY_RT];

		if ( ! empty($route) ) {
			$pos = strrpos( $route, '/' );
			if ( $pos ) {
				$this->servlet = substr( $route, 0, $pos );
				$this->action = substr( $route, $pos + 1 );
			} else {
				$this->servlet = $route;
			}
		}

		if ( empty($this->servlet) ) {
			$this->servlet = __DEFAULT_SERVLET;
		}

		if ( empty($this->action) ) {
			$this->action = __DEFAULT_ACTION;
		}

		$pos = strrpos( $this->servlet, '/' );
		if ( $pos ) {
			$this->servletName = MiscUtils::toUpperCaseFirstLetter( substr( $this->servlet, $pos + 1 ) );
			$this->servlet = substr( $this->servlet, 0, $pos + 1 ) . $this->servletName;
		} else {
			$this->servletName = MiscUtils::toUpperCaseFirstLetter( $this->servlet );
			$this->servlet = $this->servletName;
		}
		
		$this->makeFile();
	}
	
	private function makeFile() {
		$this->file = $this->path . '/' . $this->servlet . __SERVLET_SUFFIX . __CLASS_SUFFIX;
	}

}

?>
