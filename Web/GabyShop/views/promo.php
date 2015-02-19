<div id="content" style="width:90%;margin:0 auto;max-width:800px">

<h3><?= $promo['subject'] ?></h3>

<p style="text-align: left;font-weight:bold"><i><?= $promo['promo_date'] ?></i></p>

<?= $promo['content'] ?>

<div class="addthis_toolbox" style="float:right"> 
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
	if (count($related_promos) > 0) {
?>


<div style="clear:both;margin-top:60px">
<hr />
	
	<ul style="margin-left:20px;margin-top:20px">
<?
	foreach ($related_promos as $key => $value) {
?>
		<li style="margin:5px">
			<b><i><?= $value['promo_date'] ?></i></b> -
			<a style="color:blue" title="Xem chi tiết"
					href="<?= __SITE_CONTEXT . 'promo/' . $value['seo_url'] ?>">
				<?= $value['subject'] ?>
			</a>
		</li>
<?
	}
?>		
	</ul>
</div>

<?
	}
?>
	
</div>
<script src="<?= __SITE_CONTEXT ?>views/js/addthis_widget.js" type="text/javascript"></script>
<script src="<?= __SITE_CONTEXT ?>views/js/page-product.js" type="text/javascript"></script>

