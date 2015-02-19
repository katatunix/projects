<!DOCTYPE HTML>
<html>
<head>
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?= __SITE_NAME ?> - <?= htmlspecialchars($pageTitle) ?></title>

	<link rel="shortcut icon" href="<?= __UI_DIR_URL ?>/favicon.ico" type="image/x-icon">

	<link href="<?= __UI_DIR_URL ?>/css/style.css" type="text/css" rel="stylesheet">
	<link href="<?= __UI_DIR_URL ?>/jquery/jquery-ui-1.10.4/css/ui-lightness/jquery-ui-1.10.4.css" type="text/css" rel="stylesheet">

	<script src="<?= __UI_DIR_URL ?>/jquery/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="<?= __UI_DIR_URL ?>/jquery/jquery-ui-1.10.4/js/jquery-ui-1.10.4.js" type="text/javascript"></script>

</head>

<body>
<div id="wrap">
	<div class="header">
		<?php include __UI_DIR_PATH . '/HeaderUI.php'; ?>
	</div>

	<div class="content">
		<?php include __UI_DIR_PATH . '/' . $pageContent; ?>
	</div>

	<div class="footer">
		<?php include __UI_DIR_PATH . '/FooterUI.php'; ?>
	</div>

	<div class="by">
		<?php include __UI_DIR_PATH . '/ByUI.php'; ?>
	</div>
</div>
</body>
</html>
