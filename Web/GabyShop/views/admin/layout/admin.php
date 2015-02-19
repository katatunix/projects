<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	
	<title><?= __SITE_NAME . " :: " . $tile_title ?></title>
	
	<script>
		var __SITE_CONTEXT = "<?= __SITE_CONTEXT ?>";
	</script>
	
	<link href="<?= __SITE_CONTEXT ?>views/admin/css/style.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="<?= __SITE_CONTEXT ?>views/jquery/css/jquery.ui.all.css" rel="stylesheet" type="text/css" media="screen" />
	
	<script src="<?= __SITE_CONTEXT ?>views/jquery/jquery-1.4.4.min.js"></script>
	
</head>

<body>
	<div>
		<? include("views/admin/header.php"); ?>
	</div>
	
	<div id="wrapper">
		<h2><?= $tile_title ?></h2>
		
		<div id="content">
			<? include("views/" . $tile_content); ?>
		</div>
		
		<div id="footer">
			<? include("views/admin/footer.php"); ?>
		</div>
	</div>
</body>

</html>
