<?

error_reporting(E_ALL);

// Get site contex: e.g: /taskpub
$site_context = '/';
$php_self = $_SERVER['PHP_SELF'];
$pos = strrpos( $php_self, '/' );
if ( $pos ) $site_context = substr( $php_self, 0, $pos );
define('__SITE_CONTEXT', $site_context);

// Get site path: e.g: d:\AppServ\www\taskpub
$site_path = realpath(dirname(__FILE__));
define('__SITE_PATH', $site_path);

// Site info
define('__SITE_NAME',		'Task GE');
define('__SITE_SLOGAN',		'Làm việc hăng say - vận may sẽ tới');
define('__COPYRIGHT',		'nghia.buivan@gameloft.com');
define('__MY_RT',			'k');

// Directory
define('__UPLOAD_DIR',		'upload');
define('__APP_DIR',			'application');
define('__INCLUDE_DIR',		'include');

define('__CLASSES_DIR',		'classes');

define('__CONTROLLER_DIR',	__CLASSES_DIR . '/' . 'controller');
define('__MODEL_DIR',		__CLASSES_DIR . '/' . 'model');
define('__BEAN_DIR',		__CLASSES_DIR . '/' . 'bean');

define('__VIEW_DIR',			'view');
define('__VIEW_DIR_URL',		__SITE_CONTEXT . '/' . __VIEW_DIR);
define('__VIEW_DIR_PATH',		__SITE_PATH . '/' . __VIEW_DIR);

// Other
define('__CLASS_SUFFIX',		'.class.php');

define('__DEFAULT_CONTROLLER',	'index');
define('__DEFAULT_ACTION',		'index');

define('__CONTROLLER_SUFFIX',		'Controller');
define('__MODEL_SUFFIX',			'DAO');
define('__BEAN_SUFFIX',				'Bean');

define('__FILE_NOT_FOUND_CONTROLLER',		'error404');
define('__ACCESS_DENIED_CONTROLLER',		'error777');

//============================================================================================

function isStrEndWith($str, $suffix)
{
	$len_str = strlen($str);
	$len_suffix = strlen($suffix);
	if ( $len_suffix > $len_str ) return FALSE;
	return substr( $str, $len_str - $len_suffix ) == $suffix ? TRUE : FALSE;
}

function __autoload($class_name)
{
	$file_name = NULL;
	if ( isStrEndWith($class_name, __CONTROLLER_SUFFIX) )
	{
		$file_name = __SITE_PATH . '/' . __CONTROLLER_DIR . '/' . $class_name . __CLASS_SUFFIX;
	}
	else if ( isStrEndWith($class_name, __MODEL_SUFFIX) )
	{
		$file_name = __SITE_PATH . '/' . __MODEL_DIR . '/' . $class_name . __CLASS_SUFFIX;
	}
	else if ( isStrEndWith($class_name, __BEAN_SUFFIX) )
	{
		$file_name = __SITE_PATH . '/' . __BEAN_DIR . '/' . $class_name . __CLASS_SUFFIX;
	}
	else
	{
		$file_name = __SITE_PATH . '/' . __CLASSES_DIR . '/' . $class_name . __CLASS_SUFFIX;
	}
	if (is_readable($file_name))
	{
		require_once $file_name;
	}
}

function __include_file($filename)
{
	require_once __SITE_PATH . '/' . __INCLUDE_DIR . '/' . $filename;
}

//============================================================================================

include __SITE_PATH . '/' . __APP_DIR . '/BaseController'	. __CLASS_SUFFIX;
include __SITE_PATH . '/' . __APP_DIR . '/Registry'			. __CLASS_SUFFIX;
include __SITE_PATH . '/' . __APP_DIR . '/Router'			. __CLASS_SUFFIX;
include __SITE_PATH . '/' . __APP_DIR . '/Template'			. __CLASS_SUFFIX;

$registry = new Registry();

$registry->router = new Router($registry);

$registry->router->setPath(__SITE_PATH . '/' . __CONTROLLER_DIR);

$registry->template = new Template($registry);

$registry->router->loader();

?>
