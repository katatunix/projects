<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Enormous     
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20100711

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<title><?= __SITE_NAME . ' :: ' . $title ?></title>

<link rel="shortcut icon" href="<?= __VIEW_DIR_URL ?>/favicon.ico" type="image/x-icon">

<link href="<?= __VIEW_DIR_URL ?>/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?= __VIEW_DIR_URL ?>/jquery/css/jquery.ui.all.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/jquery-1.4.4.min.js"></script>
</head>

<body>

<div id="wrapper">
	<div id="header-wrapper">
		<? include __VIEW_DIR_PATH . '/header.php'; ?>
	</div>
	
	<div id="page">
		<? include __VIEW_DIR_PATH . '/' . $tileContent; ?>
		<div style="clear: both;"></div>
	</div>
	
	<div id="footer">
		<? include __VIEW_DIR_PATH . '/footer.php'; ?>
	</div>
</div>

</body>

</html>
