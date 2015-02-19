<?php

error_reporting(E_ALL);

// Get site contex: e.g: /sar
$g_site_context = '/';
$g_php_self = $_SERVER['PHP_SELF'];
$g_pos = strrpos( $g_php_self, '/' );
if ( $g_pos ) $g_site_context = substr( $g_php_self, 0, $g_pos );
define('__SITE_CONTEXT', $g_site_context);

// Get site path: e.g: e:\AppServ\www\sar
$g_site_path = realpath(dirname(__FILE__));
define('__SITE_PATH', $g_site_path);

require_once 'config/config.php';

//============================================================================================

function __import($class_name)
{
	$file_name = __SITE_PATH . '/' . __CLASSES_DIR . '/' . $class_name . __CLASS_SUFFIX;
	
	if (is_readable($file_name))
	{
		require_once $file_name;
	}
}

function __include($file_name)
{
	require_once __SITE_PATH . '/' . __INCLUDE_DIR . '/' . $file_name;
}

//============================================================================================

__import('servlet/Router');

$router = new Router();
$router->setServletDirPath(__SITE_PATH . '/' . __CLASSES_DIR . '/' . __SERVLET_DIR);
$router->executeServlet();

?>
