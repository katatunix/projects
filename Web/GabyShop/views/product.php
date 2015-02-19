<?
	require_once('includes/utils.php');
?>

<div id="content-container">

<div id="collection" style="overflow:hidden">
	<div id="left" class="arrow"><!-- --></div>
	<div id="right" class="arrow"><!-- --></div>
	<ul style="overflow:hidden">
<?
	foreach ($products_list_in_cat as $key => $value) {
		$url = __SITE_CONTEXT . 'product/' . $value['seo_url'];
?>
		<li class="item">
			<a href="<?= $url ?>" class="item-link">
				<img width="240" height="240" src="<?= __SITE_CONTEXT . __UPLOAD_DIR ?><?= get_pic_at($value['pics'], 1) ?>"
					alt="<?= $brands_list[$value['brand_id']] . ' / ' . $value['code'] ?>"
				/>
			</a>
			
			<div class="tooltip">
				<span class="indicator"><!-- --></span>
				<div class="tooltip-content">
					<div class="tooltip-brand"><?= $brands_list[$value['brand_id']] ?></div>
					<div class="tooltip-code"><?= $value['code'] ?></div>
					<p class="price"><?= format_money( $value['price_sell'] ) ?> VND</p>
				</div>
			</div>
		</li>
<?
	}
?>
	</ul>
</div>
<!--end of collection-->

<div id="content">

<?
	$product_title = $brands_list[$current_brand_id] . ' > ' . $product['code'];
?>

	<div id="left-col">
		<div id="zoom">
			<a		href="<?= __SITE_CONTEXT . __UPLOAD_DIR ?><?= $product_pics[3] ?>"
					class="cloud-zoom"
					rel="position: 'inside' , showTitle: false, adjustX:5, adjustY:5, smoothMove:1">
				<img width="480" height="480" src="<?= __SITE_CONTEXT . __UPLOAD_DIR ?><?= $product_pics[2] ?>" />
			</a>
		</div>
	</div>

	<div id="right-col">
		<h2><?= $brands_list[$current_brand_id] ?> </h2>

		<div class="code">
			<div><?= $product['code'] ?></div>
		</div>
		<pre class="description"><?= $product['description'] ?></pre>
		<span class="price"><?= format_money( $product['price_sell'] ) ?> VND</span>
		<form action="<?= __SITE_CONTEXT ?>cart/" method="post" id="purchase">
			<input type="hidden" name="id" value="<?= $product['id'] ?>" />
			<input type="image" src="<?= __SITE_CONTEXT ?>views/images/btn-add-to-cart.png?0" name="add" value="Add to cart" />
		</form>
		
		<div class="addthis_toolbox"> 
			<span class="custom_button"></span> 
			<div class="hover_menu"> 
				<div class="column1"> 
					<a class="addthis_button_email">Email</a> 
					<a class="addthis_button_twitter">Twitter</a> 
					<a class="addthis_button_facebook">Facebook</a> 
					<a class="addthis_button_myspace">MySpace</a> 
				</div> 
			</div> 
		</div> 

<?
	if ( count($products_list_in_brand) > 0 ) {
?>
		<div id="suggestions" style="display:none">
			<h3>Các sản phẩm cùng nhãn hiệu:</h3>
			<ul id="related" class="jcarousel-skin-tango">
<?
	foreach ($products_list_in_brand as $key => $value) {
		if ($key != $product['id']) {
			$url = __SITE_CONTEXT . 'product/' . $value['seo_url'];
			$product_title = $categories_list[ $value['category_id'] ][ 'name' ] . ' / ' . $value['code'];
?>
				<li>
					<a	href="<?= $url ?>"
						title="<?= $product_title ?>"> 
						<img width="100" height="100" src="<?= __SITE_CONTEXT . __UPLOAD_DIR ?><?= get_pic_at($value['pics'], 0) ?>" alt="<?= $product_title ?>" />
					</a> 
				</li> 
<?
		}
	}
?>
			</ul>
		</div>
		<!--end of suggestions-->
<?
	}
?>
	</div>
	<!--end of right-col-->
</div>
<!--end of content-->

</div>
<!--end of content-container-->
	
	
<script src="<?= __SITE_CONTEXT ?>views/js/addthis_widget.js" type="text/javascript"></script>

<script src="<?= __SITE_CONTEXT ?>views/jquery/jquery.tooltip.min.js" type="text/javascript"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/cloud-zoom.1.0.2.min.js" type="text/javascript"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/jcarousel/jquery.jcarousel.min.js" type="text/javascript"></script>

<script src="<?= __SITE_CONTEXT ?>views/js/page-product.js" type="text/javascript"></script>
<script src="<?= __SITE_CONTEXT ?>views/js/slider.js" type="text/javascript"></script>


<script type="text/javascript">
<?
	if ( count($products_list_in_brand) > 0 ) {
?>
	$('#suggestions').css('display', 'block');
	$('#related').jcarousel( { } );
<?
	}
?>
	
	$(document).ready(function(){
		$('.item-link').tooltip({
			effect:			'slide',
			fadeInSpeed:	100,
			position:		'bottom center',
			offset:			[5, 5],
			relative:		true
		});
	});
</script>
