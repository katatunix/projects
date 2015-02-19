<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title><?= __SITE_NAME . ' :: ' . $tile_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="robots" content="INDEX,FOLLOW" />
<meta name="keywords" content="gaby, gaby-shop, gaby shop, túi xách, tui xach, thời trang, thoi trang, sắc đầm, sac dam, ví nữ, vi nu, ví nam, vi nam, thời trang túi, thoi trang tui, thế giới túi, the gioi tui, giỏ xách, gio xach, phụ kiện, phu kien, varunlos, varun.los, chanel, paris hilton, gucci, louis vuitton, burbery, bandicoot, kẻ ca rô, ke ca ro, giả da rắn, gia da ran, da mềm, da mem, da thật, da that, phương thanh, thùy linh" />

<?
if ( !isset($facebook_description) ) {
	$facebook_description = 'Gaby shop chuyên cung cấp sỉ và lẻ các loại túi xách, sắc đầm và ví nữ với kiểu dáng đa dạng, hợp thời trang. Khách hàng có thể yên tâm về chất lượng và giá cả khi mua hàng của Gaby shop. Hiện nay, Gaby shop đã có chi nhánh tại Hà Nội và TP Hồ Chí Minh.';
}
if ( !isset($facebook_image) ) {
	$facebook_image = __SITE_CONTEXT . 'views/images/header-logo.png';
}
?>

<meta name="description" content="<?= $facebook_description ?>" />
<link rel="image_src" href="<?= $facebook_image ?>" />

<link rel="shortcut icon" href="<?= __SITE_CONTEXT ?>views/favicon.ico" type="image/x-icon">
<link href="<?= __SITE_CONTEXT ?>views/css/style.css" rel="stylesheet" type="text/css" media="all" />

<link href="<?= __SITE_CONTEXT ?>views/jquery/jcarousel/tango/skin.css" rel="stylesheet" type="text/css" media="all" />

<!--[if lte IE 6]>
	<script src="<?= __SITE_CONTEXT ?>views/js/unitpngfix.js" type="text/javascript"></script>
<![endif]-->

<!--[if IE 7]>
	<link href="<?= __SITE_CONTEXT ?>views/css/ie7.css" rel="stylesheet" type="text/css" media="all" />
<![endif]-->

<script>
	var __SITE_CONTEXT = "<?= __SITE_CONTEXT ?>";
</script>

<script src="<?= __SITE_CONTEXT ?>views/jquery/jquery-1.4.4.min.js" type="text/javascript"></script>

</head>

<body class="<?= $body_class ?>" id="<?= $body_id ?>">
	<div id="wrapper">
		<? include("views/header.php"); ?>
		
		<? include("views/" . $tile_content); ?>
	</div>
	<? if ($tile_footer) include("views/" . $tile_footer); ?>
</body>

</html>
