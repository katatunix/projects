<?php

class WebUtils {
	
	public static function removeSlashes($str) {
		if ( !is_string($str) ) return $str;
		if ( get_magic_quotes_gpc() )
		{
			return stripslashes($str);
		}
		return $str;
	}
	
	public static function isPOST() {
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	
	public static function isGET() {
		return $_SERVER['REQUEST_METHOD'] == 'GET';
	}
	
	public static function obtain($param) {
		if (self::isGET()) return self::obtainGET($param);
		return self::obtainPOST($param);
	}
	
	public static function obtainPOST($param) {
		if ( !isset($_POST[$param]) ) return NULL;
		if ( !is_string($_POST[$param]) ) return NULL;
		if ( strlen($_POST[$param]) <= 0 ) return NULL;
	
		return self::removeSlashes( $_POST[$param] );
	}
	
	public static function obtainGET($param) {
		if ( !isset($_GET[$param]) ) return NULL;
		if ( !is_string($_GET[$param]) ) return NULL;
		if ( strlen($_GET[$param]) <= 0 ) return NULL;
	
		return self::removeSlashes( $_GET[$param] );
	}
	
	public static function setSESSION($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	public static function getSESSION($key) {
		if ( isset($_SESSION[$key]) ) return $_SESSION[$key];
		return NULL;
	}
	
	public static function hasSESSION($key) {
		return isset($_SESSION[$key]) ? true : false;
	}
	
	public static function removeSESSION($key) {
		if ( isset($_SESSION[$key]) ) unset( $_SESSION[$key] );
	}
	
	public static function redirect($page) {
		header('LOCATION: ' . $page);
	}
	
	public static function getRequestUri() {
		return $_SERVER['REQUEST_URI'];
	}
}

?>
