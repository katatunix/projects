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
<meta http-equiv="encoding" content="utf-8" />

<title><?= __SITE_NAME . ' :: ' . $pageTitle ?></title>

<link rel="shortcut icon" href="<?= __UI_DIR_URL ?>/favicon.ico" type="image/x-icon">

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
	<div id="header-wrapper">
		<?php include __UI_DIR_PATH . '/tiles/HeaderUI.php'; ?>
	</div>
	
	<div id="page">
		<div id="content">
			<div id="content2">
				<div class="post">
					<h2 class="title"><?= $pageTitle?></h2>
					<div class="entry">
						<p>
							<?php include __UI_DIR_PATH . '/' . $pageContent; ?>
						</p>
					</div>
				</div>
				
				<?php include __UI_DIR_PATH . '/tiles/HelloUI.php'; ?>
			</div>
		</div>
		<!-- end #content -->

		<div id="sidebar">
			<?php include __UI_DIR_PATH . '/tiles/FunctionsUI.php'; ?>
			<?php include __UI_DIR_PATH . '/tiles/LogoUI.php'; ?>
		</div>
		<!-- end #sidebar -->

		<div style="clear: both;"></div>
	</div>

	<div id="footer">
		<?php include __UI_DIR_PATH . '/tiles/FooterUI.php'; ?>
	</div>
</div>

</body>

</html>
