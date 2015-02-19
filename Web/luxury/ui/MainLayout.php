<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Free Radicals
Description: A two-column, fixed-width design with dark color scheme background.
Version    : 1.0
Released   : 20081230

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="encoding" content="utf-8" />
<title><?= __SITE_NAME . ' - ' . htmlspecialchars($pageTitle) ?></title>

<link rel="shortcut icon" href="<?= __UI_DIR_URL ?>/favicon1.ico" type="image/x-icon">

<link href="<?= __UI_DIR_URL ?>/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?= __UI_DIR_URL ?>/jquery/css/jquery.ui.all.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript" src="<?= __UI_DIR_URL ?>/jquery/jquery-1.4.4.min.js"></script>

<script type="text/javascript" src="<?= __UI_DIR_URL ?>/jquery/external/jquery.bgiframe-2.1.2.js"></script>
<script type="text/javascript" src="<?= __UI_DIR_URL ?>/jquery/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?= __UI_DIR_URL ?>/jquery/ui/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="<?= __UI_DIR_URL ?>/jquery/ui/jquery.ui.mouse.min.js"></script>
<script type="text/javascript" src="<?= __UI_DIR_URL ?>/jquery/ui/jquery.ui.button.min.js"></script>
<script type="text/javascript" src="<?= __UI_DIR_URL ?>/jquery/ui/jquery.ui.draggable.min.js"></script>
<script type="text/javascript" src="<?= __UI_DIR_URL ?>/jquery/ui/jquery.ui.position.min.js"></script>
<script type="text/javascript" src="<?= __UI_DIR_URL ?>/jquery/ui/jquery.ui.dialog.min.js"></script>
<script type="text/javascript" src="<?= __UI_DIR_URL ?>/jquery/ui/jquery.ui.datepicker.min.js"></script>

<script type="text/javascript" src="<?= __UI_DIR_URL ?>/js/MyDialog.js"></script>

</head>
<body>

<div id="wrapper">
	<div id="header">
		<?php include __UI_DIR_PATH . '/HeaderUI.php'; ?>
	</div>
	<!-- end #header -->

<div id="page">
	<div id="content">
		<div class="post">
			<h1 class="title"><?= htmlspecialchars($pageTitle) ?></h1>
			<div class="entry">
				<br />
				<?php include __UI_DIR_PATH . '/' . $pageContent; ?>
			</div>
		</div>
	</div>
	<!-- end #content -->
	<div id="sidebar">
		<?php include __UI_DIR_PATH . '/SidebarUI.php'; ?>
	</div>
	<!-- end #sidebar -->
	<div style="clear:both; margin:0;"></div>
</div>
<!-- end #page -->

</div>

<div id="footer">
	<?php include __UI_DIR_PATH . '/FooterUI.php'; ?>
</div>
<!-- end #footer -->
</body>
</html>
