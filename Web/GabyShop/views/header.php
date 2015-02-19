<div id="header">

	<h1 id="logo"><a href="<?= __SITE_CONTEXT ?>"></a></h1>
	
	<ul id="global-nav" class="nav">
	<?
		foreach ($categories_list as $key => $value) {
			$class_name = $key == $current_cat_id ? 'active' : '';
			$url = __SITE_CONTEXT . 'product/' . $value['first_prod_seo_url'];
	?>
		<li class="<?= $class_name ?>">
			<a href="<?= $url ?>"><?= $value['name'] ?></a>
		</li>
	<?
		}
	?>
		<li class="<?= $is_cart_active ? ' active' : '' ?>">
			<a href="<?= __SITE_CONTEXT ?>cart/">
				<span style="color:#E48706">Giỏ hàng</span><br />
				<span class="cart-counter"><?= $cart->getQuantitySum() ?></span>
			</a>
		</li>
	</ul>
	
	<ul id="utilities" class="nav">
		<li class="<?= $is_about_active ? 'active' : '' ?>">
			<a href="<?= __SITE_CONTEXT ?>about/">Giới thiệu</a>
		</li>
		<li class="<?= $is_contact_active ? 'active' : '' ?>">
			<a href="<?= __SITE_CONTEXT ?>contact/">Liên hệ</a>
		</li>
		<li class="<?= $is_promo_active ? 'active' : '' ?>">
			<a href="<?= __SITE_CONTEXT . 'promo/' . $promo_seo_url_newest ?>">Khuyến mãi</a>
		</li>
	</ul>
	
</div>
