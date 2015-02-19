<div id="content-container">

	<div id="content">
		<h2><span><?= __SITE_NAME ?></span></h2>
		
		<h2>Spring / Summer 2011 Collection</h2>
		<br />
<?
	foreach ($categories_list as $key => $v) {
		$value = $v;
		break;
	}//
	$url = __SITE_CONTEXT . 'product/' . $value['first_prod_seo_url'];
?>
		<a href="<?= $url ?>" class="btn">View the collection</a>
	</div>
	
<?
	if ($promo_seo_url_newest) {
?>
	<div id="notice">
		<a href="<?= __SITE_CONTEXT . 'promo/' . $promo_seo_url_newest ?>">
			<div><?= $promo_subject_newest ?></div>
		</a>
	</div>
<?
	}
?>

</div>
