<?php

{
	// Get root dir, e.g. d:/xampp/htdocs/mysite/
	$rootDir = realpath(dirname(__FILE__)) . '/';
	define('__ROOT_DIR', $rootDir);

	// Get root url, e.g. /mysite/
	$rootUrl = '/';
	$phpSelf = $_SERVER['PHP_SELF'];
	$index = strrpos($phpSelf, '/');
	if ($index)
	{
		$rootUrl = substr($phpSelf, 0, $index + 1);
	}
	define('__ROOT_URL', $rootUrl);
}

function __import($class)
{
	require_once __ROOT_DIR . $class . '.php';
}
