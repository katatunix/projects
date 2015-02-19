<?php

// Site info
define('__SITE_NAME',		'SAR');
define('__SITE_SLOGAN',		'Student Attendance Register System');
define('__COPYRIGHT',		'ispcw@fgr.com');
define('__MY_RT',			'k');

// Directory will
define('__INCLUDE_DIR',		'include');

define('__CLASSES_DIR',		'classes');
define('__SERVLET_DIR',		'servlet');
define('__UI_DIR',			'ui');

// Other
define('__SERVLET_SUFFIX',				'Servlet');
define('__CLASS_SUFFIX',				'.class.php');

define('__DEFAULT_SERVLET',				'Index');
define('__DEFAULT_ACTION',				'index');

define('__PAGE_NOT_FOUND_SERVLET',		'Error404');
define('__ACCESS_DENIED_SERVLET',		'Error403');

//============================================================================================

define('__UI_DIR_URL',		__SITE_CONTEXT . '/' . __UI_DIR);
define('__UI_DIR_PATH',		__SITE_PATH . '/' . __UI_DIR);

//============================================================================================
// SESSION
define('__SKEY',					'sar123');
define('__SKEY_SSLIST',				'sar123_sslist');

//============================================================================================
// DATABASE
define('__DB_HOST',					'localhost');
define('__DB_USERNAME',				'root');
define('__DB_PASSWORD',				'');
define('__DB_NAME',					'sar');

//============================================================================================
define('__DEFAULT_PASS',					'123456');

?>
