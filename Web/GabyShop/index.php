<?
	/*** error reporting on ***/
	error_reporting(E_ERROR);
	
	/*** define the site path ***/
	$site_path = realpath(dirname(__FILE__));
	define('__SITE_PATH', $site_path);
	
	define('__SITE_NAME', 'Gaby Shop');
	define('__SITE_SLOGAN', 'Fashion or Passion');
	define('__SITE_ADDRESS', 'gaby-shop.com');
	
	define('__SITE_CONTEXT', '/gabyshop/');
	
	define('__UPLOAD_DIR', 'upload/');
	
	define('__MY_RT', 'k');
	
	/*** include the init.php file ***/
	require_once 'includes/init.php';
	
	/*** load the router ***/
	$registry->router = new Router($registry);
	
	/*** set the controller path ***/
	$registry->router->setPath(__SITE_PATH . '/controller');
	
	/*** load up the template ***/
	$registry->template = new Template($registry);
	
	/*** load the controller ***/
	$registry->router->loader();
?>
